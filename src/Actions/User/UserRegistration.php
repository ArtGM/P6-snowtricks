<?php


namespace App\Actions\User;


use App\Domain\User\RegistrationDTO;
use App\Domain\User\RegistrationFormType;
use App\Entity\User;
use App\Responders\RedirectResponders;
use App\Responders\ViewResponders;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/sign-up")
 * Class UserRegistration
 * @package App\Actions\User
 */
class UserRegistration {

	/** @var FormFactoryInterface */
	private FormFactoryInterface $formFactory;

	/** @var EntityManagerInterface */
	private EntityManagerInterface $entityManager;

	public function __construct( FormFactoryInterface $formFactory, EntityManagerInterface $entityManager) {
		$this->formFactory = $formFactory;
		$this->entityManager = $entityManager;
	}

	public function __invoke( Request $request, RedirectResponders $redirectResponder, ViewResponders $viewResponder) {
		$signUpForm = $this->formFactory->create( RegistrationFormType::class )
		                          ->handleRequest( $request );

		if ($signUpForm->isSubmitted() && $signUpForm->isValid()) {

			/** @var RegistrationDTO $registrationDto */
			$registrationDto  = $signUpForm->getData();

			$newUser = User::createFromDto($registrationDto);

			$this->entityManager->persist($newUser);
			$this->entityManager->flush();

			return $redirectResponder('homepage');

		}

		return $viewResponder('core/registration-form.html.twig', ['signUpForm' => $signUpForm->createView()]);
	}
}