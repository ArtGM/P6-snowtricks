<?php


namespace App\Actions\Ajax;


use App\Domain\Media\Handlers\MediaHandler;
use App\Domain\Media\Form\ImageFormType;
use App\Entity\Media;
use App\Repository\MediaRepository;
use App\Responders\JsonResponders;
use App\Responders\RedirectResponders;
use App\Responders\ViewResponders;
use Doctrine\ORM\EntityManagerInterface;
use Liip\ImagineBundle\Imagine\Cache\CacheManager;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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

	/** @var string */
	private string $uploadDir;

	public function __construct( EntityManagerInterface $entityManager, string $uploadDir ) {
		$this->entityManager = $entityManager;
		$this->uploadDir     = $uploadDir;
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
		RedirectResponders $redirectResponders
	): Response {

		if ( (bool) ! $request->headers->get( "snow-request" ) ) {
			return new Response( 'Forbidden Access', Response::HTTP_FORBIDDEN );
		}


		$avatarForm = $formFactory->create( ImageFormType::class, null, [ 'validation_groups' => [ 'avatar' ] ] )->handleRequest( $request );
		$imageDto   = $avatarForm->getData();

		if ( $avatarForm->isSubmitted() && $avatarForm->isValid() ) {

			$newImage = $mediaHandler->generateImage( $imageDto );

			/** @var Media $imagePath */
			$image     = $mediaRepository->findOneBy( [ 'id' => $newImage->getId() ] );
			$imagePath = $imagineCacheManager->getBrowserPath( '/uploads/' . $image->getFile(), 'avatar' );

			return $jsonResponders( [
				'validation'  => 'success',
				'newAvatarId' => $newImage->getId()->toString(),
				'newAvatar'   => $imagePath
			] );
		}

		$user = [
			'username'    => $imageDto->name,
			'description' => $imageDto->description
		];

		return $viewResponders( 'components/avatar_form.html.twig', [
			'userAvatarForm' => $avatarForm->createView(),
			'user'           => $user
		] );

	}
}
