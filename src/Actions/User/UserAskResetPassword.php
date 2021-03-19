<?php


namespace App\Actions\User;


use App\Domain\User\Password\UserAskPasswordDTO;
use App\Domain\User\Password\UserAskPasswordFormType;
use App\Entity\TokenHistory;
use App\Entity\User;
use App\Repository\UserRepository;
use App\Responders\RedirectResponders;
use App\Responders\ViewResponders;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\AuthenticationManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * Class UserConfirmAccount
 * @package App\Actions\User
 * @Route("/reset-password", name="ask_reset_password")
 */
class UserAskResetPassword {

	/**
	 * @var EntityManagerInterface
	 */
	private EntityManagerInterface $entityManager;
	/**
	 * @var FormFactoryInterface
	 */
	private FormFactoryInterface $formFactory;

	/**
	 * @var MailerInterface
	 */
	private MailerInterface $mailer;

	public function __construct(
		EntityManagerInterface $entityManager,
		FormFactoryInterface $formFactory,
		MailerInterface $mailer
	) {
		$this->entityManager = $entityManager;
		$this->formFactory   = $formFactory;
		$this->mailer        = $mailer;
	}


	public function __invoke(
		Request $request,
		ViewResponders $viewResponders,
		UserRepository $userRepository,
		RedirectResponders $redirectResponders,
		TokenStorageInterface $tokenStorage,
		AuthorizationCheckerInterface $authorizationChecker
	): Response {

		$token           = $tokenStorage->getToken();
		$isAuthenticated = $token->isAuthenticated();
		$isGranted       = $authorizationChecker->isGranted( 'ROLE_USER' );

		if ( $isAuthenticated && $isGranted ) {
			$this->sendEmailTo( $token->getUser() );

			return $redirectResponders( 'homepage' );
		}

		$emailFieldForm = $this->getForm( $request );

		return $this->handleForm( $emailFieldForm, $userRepository, $redirectResponders, $viewResponders );

	}

	/**
	 * @param $user
	 */
	private function sendEmailTo( $user ) {

		$newToken = $this->createToken( $user );
		$this->entityManager->persist( $newToken );
		$this->entityManager->flush();

		$resetPasswordUrl = $this->getResetPasswordUrl( $newToken );

		try {
			$email = $this->getTemplatedEmail( $user, $resetPasswordUrl );
			$this->mailer->send( $email );
		} catch ( TransportExceptionInterface $e ) {
			print_r( $e );
		}
	}

	/**
	 * @param $user
	 *
	 * @return TokenHistory
	 */
	private function createToken( $user ): TokenHistory {
		return TokenHistory::createToken( 'resetPassword', $user );
	}

	/**
	 * @param TokenHistory $newToken
	 * @param UrlGeneratorInterface $urlGenerator
	 *
	 * @return string
	 */
	private function getResetPasswordUrl(
		TokenHistory $newToken,
		UrlGeneratorInterface $urlGenerator
	): string {
		return $urlGenerator->generate( 'reset_password', [ 'value' => $newToken->getValue() ] );
	}

	/**
	 * @param $user
	 * @param string $resetPasswordUrl
	 *
	 * @return object|TemplatedEmail
	 */
	private function getTemplatedEmail( $user, string $resetPasswordUrl ) {
		return ( new TemplatedEmail() )
			->to( $user->getEmail() )
			->subject( 'Reset your password on Snowtrick' )
			->htmlTemplate( 'emails/reset_password.html.twig' )
			->context( [
				'resetPasswordUrl' => $resetPasswordUrl,
				'username'         => $user->getUsername(),
				'expiration_date'  => new \DateTime( '+1 day' )
			] );
	}

	/**
	 * @param Request $request
	 *
	 * @return FormInterface
	 */
	public function getForm( Request $request ): FormInterface {
		return $this->formFactory->create( UserAskPasswordFormType::class )->handleRequest( $request );
	}

	/**
	 * @param FormInterface $emailFieldForm
	 * @param UserRepository $userRepository
	 * @param RedirectResponders $redirectResponders
	 * @param ViewResponders $viewResponders
	 *
	 * @return RedirectResponse|Response
	 */
	private function handleForm( FormInterface $emailFieldForm, UserRepository $userRepository, RedirectResponders $redirectResponders, ViewResponders $viewResponders ) {
		if ( $emailFieldForm->isSubmitted() && $emailFieldForm->isValid() ) {
			/** @var UserAskPasswordDTO $userAskPasswordDto */
			$userAskPasswordDto = $emailFieldForm->getData();
			$user               = $this->getUser( $userRepository, $userAskPasswordDto );

			if ( isset( $user ) ) {

				$this->sendEmailTo( $user );

				return $redirectResponders( 'homepage' );
			}
		}

		return $viewResponders( 'core/ask_reset_password.html.twig', [
			'emailFieldForm' => $emailFieldForm->createView()
		] );
	}

	/**
	 * @param UserRepository $userRepository
	 * @param UserAskPasswordDTO $userAskPasswordDto
	 *
	 * @return User|object|null
	 */
	public function getUser( UserRepository $userRepository, UserAskPasswordDTO $userAskPasswordDto ) {
		return $userRepository->findOneBy( [ 'email' => $userAskPasswordDto->email ] );
	}
}
