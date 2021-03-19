<?php


namespace App\Actions\User;

use App\Domain\User\Password\UserResetPasswordDTO;
use App\Domain\User\Password\UserResetPasswordFormType;
use App\Entity\TokenHistory;
use App\Entity\User;
use App\Repository\TokenHistoryRepository;
use App\Repository\UserRepository;
use App\Responders\RedirectResponders;
use App\Responders\ViewResponders;
use App\Service\EncodePassword;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class UserResetPassword
 * @package App\Actions\User
 * @Route ("reset-password/{value}", name="reset_password")
 */
class UserResetPassword {
	/**
	 * @var EntityManagerInterface
	 */
	private EntityManagerInterface $entityManager;
	/**
	 * @var FormFactoryInterface
	 */
	private FormFactoryInterface $formFactory;

	public function __construct(
		EntityManagerInterface $entityManager,
		FormFactoryInterface $formFactory
	) {
		$this->entityManager = $entityManager;
		$this->formFactory   = $formFactory;
	}

	public function __invoke(
		Request $request,
		ViewResponders $viewResponders,
		UserRepository $userRepository,
		MailerInterface $mailer,
		RedirectResponders $redirectResponders,
		TokenHistoryRepository $tokenHistoryRepository,
		string $value,
		EncodePassword $encoder,
		FlashBagInterface $flashBag
	) {
		/** @var TokenHistory $token */
		$token = $tokenHistoryRepository->findOneBy( [ 'value' => $value ] );

		if ( ! $token instanceof TokenHistory ) {
			return $redirectResponders( 'homepage' );
		}
		$tokenDate   = $token->getCreatedAt();
		$currentDate = new \DateTime( 'now' );
		$interval    = $tokenDate->diff( $currentDate );

		/** @var User $user */
		$user = $userRepository->findOneBy( [ 'id' => $token->getUserId() ] );

		if ( isset( $user ) && intval( $interval->format( '%h' ) ) < 24 && $token->getType() === 'resetPassword' ) {
			$resetPasswordForm = $this->formFactory->create( UserResetPasswordFormType::class )->handleRequest( $request );

			if ( $resetPasswordForm->isSubmitted() && $resetPasswordForm->isValid() ) {
				/** @var UserResetPasswordDTO $userResetPasswordDto */
				$userResetPasswordDto = $resetPasswordForm->getData();

				$userResetPasswordDto->password = $encoder->encodedPassword( $userResetPasswordDto->password );

				$newUserPassword = $user->updatePassword( $userResetPasswordDto->password );

				$this->entityManager->persist( $newUserPassword );
				$this->entityManager->flush();
				$flashBag->add( 'success', 'Your password has been successfully reset !' );

				return $redirectResponders( 'homepage' );

			}

			return $viewResponders( 'core/reset_password.html.twig', [
				'resetPasswordForm' => $resetPasswordForm->createView()
			] );
		}

		return $redirectResponders( 'homepage' );
	}

}