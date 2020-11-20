<?php


namespace App\Actions\User;


use App\Domain\User\Registration\RegistrationDTO;
use App\Domain\User\Registration\RegistrationFormType;
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
		ViewResponders $viewResponder
	) {
		$signUpForm = $this->signUpForm( $request );

		if ( $signUpForm->isSubmitted() && $signUpForm->isValid() ) {

			/** @var RegistrationDTO $registrationDto */
			$registrationDto = $signUpForm->getData();

			$registrationDto->password = $this->encodedPassword( $registrationDto->password );

			$newUser = User::createFromDto( $registrationDto );


			$this->entityManager->persist( $newUser );

			$this->entityManager->flush();

			return $redirectResponder( 'homepage' );


		}

		return $viewResponder( 'core/registration-form.html.twig', [ 'signUpForm' => $signUpForm->createView() ] );
	}

	/**
	 * @param $request
	 *
	 * @return FormInterface
	 */
	private function signUpForm( $request ): FormInterface {
		return $this->formFactory->create( RegistrationFormType::class )->handleRequest( $request );
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