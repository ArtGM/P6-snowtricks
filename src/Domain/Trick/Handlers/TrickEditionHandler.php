<?php


namespace App\Domain\Trick\Handlers;


use App\Actions\Media\MediaCreation;
use App\Domain\Trick\TrickDTO;
use App\Entity\Trick;
use App\Service\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormInterface;

class TrickEditionHandler {

	private EntityManagerInterface $entityManager;

	private FileUploader $fileUploader;

	private MediaCreation $mediaCreation;

	/**
	 * TrickEditionHandler constructor.
	 *
	 * @param EntityManagerInterface $entityManager
	 * @param FileUploader $fileUploader
	 * @param MediaCreation $mediaCreation
	 */
	public function __construct(
		EntityManagerInterface $entityManager,
		FileUploader $fileUploader,
		MediaCreation $mediaCreation
	) {
		$this->entityManager = $entityManager;
		$this->fileUploader  = $fileUploader;
		$this->mediaCreation = $mediaCreation;

	}

	/**
	 * @param FormInterface $trickEditionForm
	 * @param Trick $trick
	 */
	public function handle( FormInterface $trickEditionForm, Trick $trick ) {

		/** @var TrickDTO $trickDto */
		$trickDto = $trickEditionForm->getData();

		$medias = $trick->get_medias();

		if ( ! empty( $trickDto->images ) ) {
			foreach ( $trickDto->images as $mediaDTO ) {

				$fileType          = $mediaDTO->file->getClientMimeType();
				$fileWithExtension = $this->fileUploader->upload( $mediaDTO->file );
				$medias[]          = $this->mediaCreation->generateMediaEntity( $mediaDTO, $fileWithExtension, $fileType );
			}
		}
		$trick->update( $trickDto, $medias );
		$this->entityManager->persist( $trick );
		$this->entityManager->flush();

	}

}