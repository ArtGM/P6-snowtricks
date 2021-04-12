<?php


namespace App\Domain\Trick\Handlers;


use App\Domain\Media\Handlers\MediaHandler;
use App\Domain\Media\ImageDTO;
use App\Domain\Media\VideoDTO;
use App\Domain\Trick\TrickDTO;
use App\Entity\Media;
use App\Entity\Trick;
use App\Service\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormInterface;

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
	public function manageTrickEdition( FormInterface $trickEditionForm, Trick $trick ) {

		/** @var TrickDTO $trickDto */
		$trickDto = $trickEditionForm->getData();

		$images = $this->removeEmptyObject( $trickDto->images );
		$videos = $this->removeEmptyObject( $trickDto->video );

		$modifiedMedias = $this->replaceMediasFromDto( array_merge( $images, $videos ), $trick );

		$updatedTrick = $trick->update( $trickDto, $modifiedMedias );

		$this->entityManager->persist( $updatedTrick );
		$this->entityManager->flush();

	}

	/**
	 * @param $array
	 *
	 * @return array
	 */
	private function removeEmptyObject( $array ): array {
		$newArray = [];
		foreach ( $array as $item ) {
			$toArray = (array) $item;
			if ( array_filter( $toArray ) ) {
				$newArray[] = $item;
			}
		}

		return $newArray;
	}

	/**
	 * @param array $DTOs
	 * @param Trick $trick
	 *
	 * @return array
	 */
	private function replaceMediasFromDto( array $DTOs, Trick $trick ): array {

		$medias = $trick->getMedias()->getValues();

		$newMedias = $this->addNewMedia( $DTOs );

		$medias = $this->deleteMedias( $DTOs, $medias, $trick );

		$updatedMedias = $this->updateMediaIfModified( $DTOs, $medias );

		return array_merge( $medias, $updatedMedias, $newMedias );

	}

	/**
	 * @param $mediaDto
	 *
	 * @return Media|null
	 */
	private function generateMedia( $mediaDto ): ?Media {
		if ( $this->isImage( $mediaDto ) ) {
			return $this->mediaCreation->generateImage( $mediaDto );
		}
		if ( $this->isVideo( $mediaDto ) ) {
			return $this->mediaCreation->generateVideo( $mediaDto );
		}

		return null;
	}

	/**
	 * @param $mediaDTO
	 *
	 * @return bool
	 */
	private function isImage( $mediaDTO ): bool {
		return $mediaDTO instanceof ImageDTO;
	}

	/**
	 * @param $mediaDTO
	 *
	 * @return bool
	 */
	private function isVideo( $mediaDTO ): bool {
		return $mediaDTO instanceof VideoDTO;
	}

	/**
	 * @param array $mediasIdToDelete
	 * @param array $medias
	 *
	 * @return array
	 */
	private function getMediaToDelete( array $mediasIdToDelete, array $medias ): array {
		$mediasToBeDeleted = [];
		foreach ( $medias as $media ) {
			if ( array_search( $media->getId()->toString(), $mediasIdToDelete ) !== false ) {
				$mediasToBeDeleted[] = $media;
			}
		}

		return $mediasToBeDeleted;
	}

	/**
	 * @param array $DTOs
	 *
	 * @return array
	 */
	private function addNewMedia( array $DTOs ): array {
		$newMedias = [];

		foreach ( $DTOs as $key => $mediaDto ) {
			if ( empty( $mediaDto->id ) ) {
				$newMedias[] = $this->generateMedia( $mediaDto );
				unset( $DTOs[ $key ] );
			}
		}

		return $newMedias;
	}

	/**
	 * @param array $DTOs
	 * @param array $medias
	 * @param Trick $trick
	 *
	 * @return array
	 */
	private function deleteMedias( array $DTOs, array $medias, Trick $trick ): array {
		$dataTransferObjectIds = array_map( fn( $obj ) => $obj->id, $DTOs );
		$mediaEntityIds        = array_map( fn( $obj ) => $obj->getId()->toString(), $medias );

		$mediasIdToDelete = array_diff( $mediaEntityIds, $dataTransferObjectIds );
		$mediaToDelete    = $this->getMediaToDelete( $mediasIdToDelete, $medias );

		foreach ( $mediaToDelete as $key => $media ) {
			$medias = $trick->removeMedia( $media );
		}

		return $medias;
	}

	/**
	 * @param array $DTOs
	 * @param array $medias
	 *
	 * @return array
	 */
	private function updateMediaIfModified( array $DTOs, array $medias ): array {
		$updatedMedias = [];
		foreach ( $DTOs as $mediaDto ) {

			$name = $this->isVideo( $mediaDto ) ? $mediaDto->title : $mediaDto->name;

			foreach ( $medias as $media ) {
				if ( $media->getId()->toString() === $mediaDto->id ) {
					if ( ! empty( $mediaDto->file ) ) {
						$updatedMedias[] = $this->generateMedia( $mediaDto );
					}

					if ( $media->getName() !== $name || $media->getDescription() !== $mediaDto->description ) {
						$media->updateName( $mediaDto->name );
						$media->updateDescription( $mediaDto->description );
					}
				}
			}
		}

		return $updatedMedias;
	}

}
