<?php


namespace App\Actions\Ajax;


use App\Domain\Media\Handlers\MediaHandler;
use App\Domain\Media\ImageDTO;
use App\Domain\Media\ImageFormType;
use App\Entity\Media;
use App\Repository\MediaRepository;
use App\Responders\JsonResponders;
use App\Responders\ViewResponders;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
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
		MediaRepository $mediaRepository

	): Response {

		$imageData = $request->request->all('image_form');

		$imageDto = new ImageDTO();
		$imageDto->name = $imageData['name'];
		$imageDto->description = $imageData['description'];
		$uploadedFile          = $request->files->get('image_form');
		$imageDto->file = $uploadedFile['file'];
		$newImage = $mediaHandler->generateImage($imageDto);


		return $jsonResponders( [
			'newAvatar' => $newImage->getId()->toString()
		] );

	}
}