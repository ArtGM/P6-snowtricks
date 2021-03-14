<?php


namespace App\Actions\User;


use App\Domain\User\Password\UserAskPasswordDTO;
use App\Domain\User\Password\UserAskPasswordFormType;
use App\Entity\TokenHistory;
use App\Repository\UserRepository;
use App\Responders\RedirectResponders;
use App\Responders\ViewResponders;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;
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
	 * @var RequestStack
	 */
	private RequestStack $requestStack;
	/**
	 * @var MailerInterface
	 */
	private MailerInterface $mailer;

	public function __construct(
		EntityManagerInterface $entityManager,
		FormFactoryInterface $formFactory,
		RequestStack $requestStack,
		MailerInterface $mailer
	) {
		$this->entityManager = $entityManager;
		$this->formFactory   = $formFactory;
		$this->requestStack  = $requestStack;
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
		$userRoles       = $token->getRoleNames();
		$isGranted       = $authorizationChecker->isGranted( 'ROLE_USER' );

		if ( $isAuthenticated && $isGranted ) {
			$this->sendEmailTo( $token->getUser() );

			return $redirectResponders( 'homepage' );
		} else {

			$emailFieldForm = $this->formFactory->create( UserAskPasswordFormType::class )->handleRequest( $request );

			if ( $emailFieldForm->isSubmitted() && $emailFieldForm->isValid() ) {
				/** @var UserAskPasswordDTO $userAskPasswordDto */
				$userAskPasswordDto = $emailFieldForm->getData();
				$user               = $userRepository->findOneBy( [ 'email' => $userAskPasswordDto->email ] );

				if ( isset( $user ) ) {

					$this->sendEmailTo( $user );

					return $redirectResponders( 'homepage' );
				}
			}

			return $viewResponders( 'core/ask_reset_password.html.twig', [
				'emailFieldForm' => $emailFieldForm->createView()
			] );
		}

	}

	private function sendEmailTo( $user ) {
		$newToken = TokenHistory::createToken( 'resetPassword', $user );
		$this->entityManager->persist( $newToken );
		$this->entityManager->flush();

		$resetPasswordUrl = 'http://localhost:8000/reset-password/' . $newToken->getValue();

		try {
			$email = ( new TemplatedEmail() )
				->to( $user->getEmail() )
				->subject( 'Reset your password on Snowtrick' )
				->htmlTemplate( 'emails/reset_password.html.twig' )
				->context( [
					'resetPasswordUrl' => $resetPasswordUrl,
					'username'         => $user->getUsername(),
					'expiration_date'  => new \DateTime( '+1 day' )
				] );

			$this->mailer->send( $email );
		} catch ( TransportExceptionInterface $e ) {
			print_r( $e );
		}
	}
}
