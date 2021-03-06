<?php


namespace App\Actions\User;

use App\Domain\Factory\MediaDtoFactory;
use App\Domain\Factory\UserDtoFactory;
use App\Domain\Media\Form\ImageFormType;
use App\Domain\User\Profile\Form\UserProfileFormType;
use App\Repository\MediaRepository;
use App\Repository\UserRepository;
use App\Responders\RedirectResponders;
use App\Responders\ViewResponders;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;


/**
 * Class UserShowProfile
 * @package App\Actions\User
 * @Route("/user/profile-{id}", name="user-profile")
 */
class UserShowProfile {

	/** @var FormFactoryInterface */
	private FormFactoryInterface $formFactory;
	/**
	 * @var UserDtoFactory
	 */
	private UserDtoFactory $userDtoFactory;
	/**
	 * @var MediaDtoFactory
	 */
	private MediaDtoFactory $mediaDtoFactory;
	/**
	 * @var EntityManagerInterface
	 */
	private EntityManagerInterface $entityManager;

	public function __construct( FormFactoryInterface $formFactory, UserDtoFactory $userDtoFactory, MediaDtoFactory $mediaDtoFactory, EntityManagerInterface $entityManager ) {
		$this->formFactory     = $formFactory;
		$this->userDtoFactory  = $userDtoFactory;
		$this->mediaDtoFactory = $mediaDtoFactory;
		$this->entityManager   = $entityManager;
	}

	public function __invoke(
		Request $request,
		TokenStorageInterface $tokenStorage,
		ViewResponders $viewResponders,
		RedirectResponders $redirectResponders,
		UserRepository $userRepository,
		MediaRepository $mediaRepository,
		FlashBagInterface $flashBag,
		string $id,
		AuthorizationCheckerInterface $authorizationChecker
	): Response {

		if ( ! $authorizationChecker->isGranted( 'ROLE_USER' ) ) {
			$flashBag->add( 'warning', 'access is restricted' );

			return $redirectResponders( 'homepage' );
		}

		$user    = $userRepository->find( $id );
		$userDto = $this->userDtoFactory->create( $user );

		$avatarId        = $user->getAvatar();
		$avatar          = isset( $avatarId ) ? $mediaRepository->findOneBy( [ 'id' => $avatarId ] ) : null;
		$mediaDto        = $avatar !== null ? $this->mediaDtoFactory->createImage( $avatar ) : null;
		$userProfileForm = $this->formFactory->create( UserProfileFormType::class, $userDto )->handleRequest( $request );
		$userAvatarForm  = $this->formFactory->create( ImageFormType::class, $mediaDto )->handleRequest( $request );

		$templateVars = [
			'user'            => $user,
			'avatarName'      => $mediaDto ? $mediaDto->file->getFilename() : null,
			'userProfileForm' => $userProfileForm->createView(),
			'userAvatarForm'  => $userAvatarForm->createView()
		];

		if ( $userProfileForm->isSubmitted() && $userProfileForm->isValid() ) {
			$userDto = $userProfileForm->getData();

			$newAvatar = $mediaRepository->findOneBy( [ 'id' => $userDto->avatar ] );

			$updatedUser = $user->update( $userDto, $newAvatar );


			$this->entityManager->persist( $updatedUser );
			$this->entityManager->flush();
			$flashBag->add( 'success', 'your profile has been updated' );

			return $redirectResponders( 'homepage' );
		}

		return $viewResponders( 'core/user_profile.html.twig', $templateVars );
	}
}
