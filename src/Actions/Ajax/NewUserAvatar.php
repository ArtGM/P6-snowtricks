<?php


namespace App\Actions\Ajax;


use App\Domain\Media\Handlers\MediaHandler;
use App\Domain\Media\ImageFormType;
use App\Entity\Media;
use App\Entity\User;
use App\Repository\MediaRepository;
use App\Responders\JsonResponders;
use App\Responders\ViewResponders;
use Doctrine\ORM\EntityManagerInterface;
use Liip\ImagineBundle\Imagine\Cache\CacheManager;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

/**
 * Class NewUserAvatar
 * @package App\Actions\Ajax
 * @Route("/user/profile/handle-avatar/", name="handle-avatar")
 */
class NewUserAvatar {


	/**
	 * @var EntityManagerInterface
	 */
	private EntityManagerInterface $entityManager;

	/** @var string  */
	private string $uploadDir;

	public function __construct(EntityManagerInterface $entityManager, string $uploadDir) {
		$this->entityManager = $entityManager;
		$this->uploadDir = $uploadDir;
	}

	public function __invoke(
		Request $request,
		JsonResponders $jsonResponders,
		MediaHandler $mediaHandler,
		ImageFormType $imageFormType,
		FormFactoryInterface $formFactory,
		MediaRepository $mediaRepository,
		ViewResponders $viewResponders,
		CacheManager $imagineCacheManager,
		TokenStorage $tokenStorage

	): Response {
		$avatarForm = $formFactory->create(ImageFormType::class, null, ['validation_groups' => ['avatar']])->handleRequest($request);

		$imageDto = $avatarForm->getData();

		if ($avatarForm->isSubmitted() && $avatarForm->isValid()) {
			/** @var User $currentUser */
			$currentUser = $tokenStorage->getToken()->getUser();
			$oldAvatar   = $mediaRepository->findOneBy( [ 'id' => $currentUser->getAvatar() ] );
			$this->entityManager->remove( $oldAvatar );
			$newImage = $mediaHandler->generateImage( $imageDto );

			/** @var Media $imagePath */
			$image     = $mediaRepository->findOneById( [ $newImage->getId() ] );
			$imagePath = $imagineCacheManager->getBrowserPath( '/uploads/' . $image->getFile(), 'avatar' );

			return $jsonResponders( [
				'validation'  => 'success',
				'newAvatarId' => $newImage->getId()->toString(),
				'newAvatar'   => $imagePath
			] );
		}

		$user = [
			'username' => $imageDto->name,
			'description' => $imageDto->description
		];

		return $viewResponders('components/avatar_form.html.twig', [
			'userAvatarForm' => $avatarForm->createView(),
			'user' => $user
		]);

	}
}