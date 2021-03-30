<?php


namespace App\Actions\User;


use App\Entity\TokenHistory;
use App\Entity\User;
use App\Repository\TokenHistoryRepository;
use App\Repository\UserRepository;
use App\Responders\RedirectResponders;
use App\Responders\ViewResponders;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * Class UserConfirmAccount
 * @package App\Actions\User
 * @Route("/confirm-account/{value}", name="confirm_account")
 */
class UserConfirmAccount {

	/**
	 * @var EntityManagerInterface
	 */
	private EntityManagerInterface $entityManager;

	public function __construct( EntityManagerInterface $entityManager ) {
		$this->entityManager = $entityManager;
	}


	public function __invoke(
		ViewResponders $viewResponders,
		string $value,
		TokenHistoryRepository $tokenHistoryRepository,
		UserRepository $userRepository,
		RedirectResponders $redirectResponders,
		AuthorizationCheckerInterface $authorizationChecker
	): Response {

		if ( ! $authorizationChecker->isGranted( 'ROLE_USER' ) ) {
			return $redirectResponders( 'homepage' );
		}

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


		if ( isset( $user ) && intval( $interval->format( '%h' ) ) < 24 ) {
			$user->confirmAccount();
			$this->entityManager->persist( $user );
			$this->entityManager->flush();
		}

		return $viewResponders( 'core/account_confirmed.html.twig', [
			'confirmation' => isset( $user ) ? $user->isConfirmed() : null
		] );
	}
}