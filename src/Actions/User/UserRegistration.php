<?php


namespace App\Actions\User;


use App\Domain\User\Registration\UserRegistrationDTO;
use App\Domain\User\Registration\UserRegistrationFormType;
use App\Entity\TokenHistory;
use App\Entity\User;
use App\Responders\RedirectResponders;
use App\Responders\ViewResponders;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
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
	 *
	 * @return RedirectResponse|Response
	 */
	public function __invoke(
		Request $request,
		RedirectResponders $redirectResponder,
		ViewResponders $viewResponder,
		MailerInterface $mailer
	) {
		$signUpForm = $this->signUpForm( $request );

		if ( $signUpForm->isSubmitted() && $signUpForm->isValid() ) {

			/** @var UserRegistrationDTO $registrationDto */
			$registrationDto = $signUpForm->getData();

			$registrationDto->password = $this->encodedPassword( $registrationDto->password );

			$newUser = User::createFromDto( $registrationDto );
			$this->entityManager->persist( $newUser );
			$this->entityManager->flush();

			$token             = TokenHistory::createToken( 'confirmAccount', $newUser->getId() );
			$confirmAccountUrl = 'http://localhost:8000/confirm-account/' . $token->getValue();
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
			} catch ( \Exception $e ) {
				dd( $e );
			}

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

	/**
	 * @param $plainPassword
	 *
	 * @return string
	 */
	private function encodedPassword( $plainPassword ): string {
		$passwordEncoder = $this->encoder->getEncoder( User::class );

		return $passwordEncoder->encodePassword( $plainPassword, null );
	}

}