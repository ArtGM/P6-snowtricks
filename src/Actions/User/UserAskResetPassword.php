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
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;

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

	public function __construct( EntityManagerInterface $entityManager, FormFactoryInterface $formFactory ) {
		$this->entityManager = $entityManager;
		$this->formFactory   = $formFactory;
	}


	public function __invoke( Request $request, ViewResponders $viewResponders, UserRepository $userRepository, MailerInterface $mailer, RedirectResponders $redirectResponders ): Response {

		$emailFieldForm = $this->formFactory->create( UserAskPasswordFormType::class )->handleRequest( $request );

		if ( $emailFieldForm->isSubmitted() && $emailFieldForm->isValid() ) {
			/** @var UserAskPasswordDTO $userAskPasswordDto */
			$userAskPasswordDto = $emailFieldForm->getData();
			$user               = $userRepository->findOneBy( [ 'email' => $userAskPasswordDto->email ] );

			if ( isset( $user ) ) {
				$token = TokenHistory::createToken( 'resetPassword', $user );
				$this->entityManager->persist( $token );
				$this->entityManager->flush();
				$resetPasswordUrl = 'http://localhost:8000/reset-password/' . $token->getValue();

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

					$mailer->send( $email );
				} catch ( TransportExceptionInterface $e ) {
					print_r( $e );
				}

				return $redirectResponders( 'homepage' );
			}
		}

		return $viewResponders( 'core/ask_reset_password.html.twig', [
			'emailFieldForm' => $emailFieldForm->createView()
		] );
	}
}