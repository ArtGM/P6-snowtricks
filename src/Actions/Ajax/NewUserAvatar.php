<?php


namespace App\Actions\Ajax;


use App\Domain\Media\Handlers\MediaHandler;
use App\Domain\Media\ImageFormType;
use App\Repository\MediaRepository;
use App\Responders\JsonResponders;
use App\Responders\ViewResponders;
use Doctrine\ORM\EntityManagerInterface;
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
		ViewResponders $viewResponders

	): Response {
		$avatarForm = $formFactory->create(ImageFormType::class, null, ['validation_groups' => ['avatar']])->handleRequest($request);

		$imageDto = $avatarForm->getData();

		if ($avatarForm->isSubmitted() && $avatarForm->isValid()) {
			$newImage = $mediaHandler->generateImage($imageDto);
			return $jsonResponders( [
				'validation' => 'success',
				'newAvatar' => $newImage->getId()->toString()
			] );
		}
		$user = [
			'username' => $imageDto->name,
			'description' => $imageDto->description
		];

		return $viewResponders('components/avatar_form.html.twig', [
			'validation' => 'error',
			'userAvatarForm' => $avatarForm->createView(),
			'user' => $user
		]);

	}
}