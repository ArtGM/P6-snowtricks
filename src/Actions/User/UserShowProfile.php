<?php


namespace App\Actions\User;

use App\Domain\Factory\UserDtoFactory;
use App\Domain\Media\ImageDTO;
use App\Domain\Media\ImageFormType;
use App\Domain\User\Profile\UserProfileFormType;
use App\Repository\MediaRepository;
use App\Repository\UserRepository;
use App\Responders\RedirectResponders;
use App\Responders\ViewResponders;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;


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

	public function __construct( FormFactoryInterface $formFactory, UserDtoFactory $userDtoFactory ) {
		$this->formFactory    = $formFactory;
		$this->userDtoFactory = $userDtoFactory;
	}

	public function __invoke(
		Request $request,
		TokenStorageInterface $tokenStorage,
		ViewResponders $viewResponders,
		RedirectResponders $redirectResponders,
		UserRepository $userRepository,
		MediaRepository $mediaRepository,
		string $id ): Response {
		$token           = $tokenStorage->getToken();
		$isAuthenticated = $token->isAuthenticated();
		if ( $isAuthenticated ) {
			$user            = $userRepository->find( $id );
			$userDto         = $this->userDtoFactory->create( $user );

			$avatar = $user->getAvatar();
			$mediaDto = isset($avatar) ? $mediaRepository->find($avatar) : null;

			$userProfileForm = $this->formFactory->create( UserProfileFormType::class, $userDto )->handleRequest( $request );
			$userAvatarForm  = $this->formFactory->create(ImageFormType::class, $mediaDto)->handleRequest($request);

			$templateVars = [
				'user' => $user,
				'userProfileForm' => $userProfileForm->createView(),
				'userAvatarForm'  => $userAvatarForm->createView()
			];

			return $viewResponders( 'core/user_profile.html.twig', $templateVars);
		}

		return $redirectResponders( 'user_login' );
	}
}