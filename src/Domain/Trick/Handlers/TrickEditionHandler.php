<?php


namespace App\Domain\Trick\Handlers;


use App\Domain\Media\Handlers\MediaHandler;
use App\Domain\Media\ImageDTO;
use App\Domain\Trick\TrickDTO;
use App\Entity\Media;
use App\Entity\Trick;
use App\Service\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormInterface;
use ReflectionProperty;

class TrickEditionHandler {

	private EntityManagerInterface $entityManager;

	private FileUploader $fileUploader;

	private MediaHandler $mediaCreation;

	/**
	 * TrickEditionHandler constructor.
	 *
	 * @param EntityManagerInterface $entityManager
	 * @param FileUploader $fileUploader
	 * @param MediaHandler $mediaCreation
	 */
	public function __construct(
		EntityManagerInterface $entityManager,
		FileUploader $fileUploader,
		MediaHandler $mediaCreation
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

		$modifiedMedias = $this->replaceMediasFromDto( $trickDto->images, $trick );
		$updatedTrick   = $trick->update( $trickDto, $modifiedMedias );
		$this->entityManager->persist( $updatedTrick );
		$this->entityManager->flush();

	}

	/**
	 * @param array $dto
	 * @param Trick $trick
	 *
	 * @return array
	 */
	private function replaceMediasFromDto( array $dto, Trick $trick ): array {
		$rp                  = new ReflectionProperty( 'App\Domain\Media\ImageDTO', 'id' );
		$i                   = 0;
		$j                   = 0;
		$medias              = $trick->getMedias()->getValues();
		$mediasToAddOrUpdate = [];
		while ( $i < count( $dto ) || $j < count( $medias ) ) {

			/** @var ImageDTO $mediaDTO */
			$mediaDTO = $dto[ $i ];

			/** @var Media $mediaEntity */
			$mediaEntity = isset( $medias[ $j ] ) ? $medias[ $j ] : null;
			dump( $mediaDTO );
			if ( ! $rp->isInitialized( $mediaDTO ) ) {
				$fileType              = $mediaDTO->file->getClientMimeType();
				$fileWithExtension     = $this->fileUploader->upload( $mediaDTO->file );
				$newMediaEntity        = $this->mediaCreation->generateMediaEntity( $mediaDTO, $fileWithExtension, $fileType );
				$mediasToAddOrUpdate[] = $newMediaEntity;
			} elseif ( $mediaDTO->id === (string) $mediaEntity->getId() ) {
				if ( isset( $mediaEntity ) && $mediaDTO->name !== $mediaEntity->getName() ) {
					$mediaEntity->updateName( $mediaDTO->name );
				}
				if ( $mediaDTO->description !== $mediaEntity->getDescription() ) {
					$mediaEntity->updateDescription( $mediaDTO->description );
				}
				$mediasToAddOrUpdate[] = $mediaEntity;
				$j ++;
			} else {
				$trick->removeMedia( $mediaEntity );
				$j ++;
			}
			$i ++;
		}

		return $mediasToAddOrUpdate;
	}

}