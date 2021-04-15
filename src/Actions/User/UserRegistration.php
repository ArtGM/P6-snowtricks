<?php


namespace App\Actions\User;


use App\Domain\User\Registration\UserRegistrationDTO;
use App\Domain\User\Registration\Form\UserRegistrationFormType;
use App\Entity\TokenHistory;
use App\Entity\User;
use App\Responders\RedirectResponders;
use App\Responders\ViewResponders;
use App\Service\EncodePassword;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Mailer\Exception\TransportException;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;

/**
 * @Route("/sign-up", name="user_registration")
 * Class UserRegistration
 * @package App\Actions\User
 */
class UserRegistration {

	/** @var FormFactoryInterface */
	private FormFactoryInterface $formFactory;

	/** @var EntityManagerInterface */
	private EntityManagerInterface $entityManager;

	/** @var EncoderFactoryInterface */
	private EncoderFactoryInterface $encoder;

	/**
	 * UserRegistration constructor.
	 *
	 * @param FormFactoryInterface $formFactory
	 * @param EntityManagerInterface $entityManager
	 * @param EncoderFactoryInterface $encoder
	 */
	public function __construct(
		FormFactoryInterface $formFactory,
		EntityManagerInterface $entityManager,
		EncoderFactoryInterface $encoder
	) {
		$this->formFactory   = $formFactory;
		$this->entityManager = $entityManager;
		$this->encoder       = $encoder;
	}

	/**
	 * @param Request $request
	 * @param RedirectResponders $redirectResponder
	 * @param ViewResponders $viewResponder
	 * @param MailerInterface $mailer
	 * @param EncodePassword $encoder
	 * @param UrlGeneratorInterface $urlGenerator
	 * @param FlashBagInterface $flashBag
	 * @param AuthorizationCheckerInterface $authorizationChecker
	 *
	 * @return RedirectResponse|Response
	 */
	public function __invoke(
		Request $request,
		RedirectResponders $redirectResponder,
		ViewResponders $viewResponder,
		MailerInterface $mailer,
		EncodePassword $encoder,
		UrlGeneratorInterface $urlGenerator,
		FlashBagInterface $flashBag,
		AuthorizationCheckerInterface $authorizationChecker
	) {
		if ( $authorizationChecker->isGranted( 'ROLE_USER' ) ) {
			return $redirectResponder( 'homepage' );
		}

		$signUpForm = $this->signUpForm( $request );

		if ( $signUpForm->isSubmitted() && $signUpForm->isValid() ) {

			/** @var UserRegistrationDTO $registrationDto */
			$registrationDto = $signUpForm->getData();

			$registrationDto->password = $encoder->encodedPassword( $registrationDto->password );

			$newUser = User::createFromDto( $registrationDto );
			$this->entityManager->persist( $newUser );


			$token = TokenHistory::createToken( 'confirmAccount', $newUser );
			$this->entityManager->persist( $token );

			$this->entityManager->flush();

			$confirmAccountUrl = $urlGenerator->generate( 'confirm_account', [ 'value' => $token->getValue() ], UrlGeneratorInterface::ABSOLUTE_URL );

			try {
				$email = ( new TemplatedEmail() )
					->to( $registrationDto->email )
					->subject( 'Thank you for signing up to Snowtrick Community !' )
					->htmlTemplate( 'emails/signup.html.twig' )
					->context( [
						'confirmAccountUrl' => $confirmAccountUrl,
						'username'          => $registrationDto->name,
						'expiration_date'   => new \DateTime( '+1 day' )
					] );

				$mailer->send( $email );
			} catch ( TransportExceptionInterface $e ) {
				throw new TransportException();
			}
			$flashBag->add( 'warning', 'Account created, please confirm your account before login by clicking on the email\'s link' );

			return $redirectResponder( 'homepage' );


		}

		return $viewResponder( 'core/registration_form.html.twig', [ 'signUpForm' => $signUpForm->createView() ] );
	}

	/**
	 * @param $request
	 *
	 * @return FormInterface
	 */
	private function signUpForm( $request ): FormInterface {
		return $this->formFactory->create( UserRegistrationFormType::class )->handleRequest( $request );
	}


}
