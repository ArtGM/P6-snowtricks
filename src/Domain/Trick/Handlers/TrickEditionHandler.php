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
	 * @param $array
	 *
	 * @return array
	 */
	private function removeEmptyObject( $array ) {
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
	 * @param FormInterface $trickEditionForm
	 * @param Trick $trick
	 */
	public function handle( FormInterface $trickEditionForm, Trick $trick ) {

		/** @var TrickDTO $trickDto */
		$trickDto = $trickEditionForm->getData();

		$images = $this->removeEmptyObject( $trickDto->images );
		$videos = $this->removeEmptyObject( $trickDto->video );

		$modifiedMedias = $this->replaceMediasFromDto( array_merge( $images, $videos ), $trick );
		$updatedTrick   = $trick->update( $trickDto, $modifiedMedias );
		$this->entityManager->persist( $updatedTrick );
		$this->entityManager->flush();

	}

	/**
	 * @param array $DTOs
	 * @param Trick $trick
	 *
	 * @return array
	 */
	private function replaceMediasFromDto( array $DTOs, Trick $trick ): array {

		$medias              = $trick->getMedias()->getValues();
		$mediasToAddOrUpdate = [];

		foreach ( $DTOs as $key => $mediaDto ) {
			if ( empty( $mediaDto->id ) ) {
				$mediasToAddOrUpdate[] = $this->generateMedia( $mediaDto );
				unset( $DTOs[ $key ] );
			}
		}

		$dataTransferObjectIds = array_map( fn( $obj ) => $obj->id, $DTOs );
		$mediaEntityIds        = array_map( fn( $obj ) => $obj->getId()->toString(), $medias );

		$mediasIdToDelete = array_diff( $mediaEntityIds, $dataTransferObjectIds );
		$mediaToDelete    = $this->getMediaToDelete( $mediasIdToDelete, $medias );

		foreach ( $mediaToDelete as $key => $media ) {
			$trick->removeMedia( $media );
			unset( $medias[ $key ] );
		}

		foreach ( $medias as $media ) {
			$mediasToAddOrUpdate[] = $media;
		}

		return $mediasToAddOrUpdate;

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
	 * @param Media $mediaEntity
	 * @param ImageDTO|VideoDTO $mediaDTO
	 *
	 * @return mixed
	 */
	private function updateMediaEntity( Media $mediaEntity, $mediaDTO ): Media {

		if ( 'video' === $mediaEntity->getType() ) {
			$name = $mediaDTO->title;
			$file = $mediaDTO->url;
		} else {
			$name = $mediaDTO->name;
			$file = $mediaDTO->file;
		}

		if ( isset( $mediaEntity ) && $name !== $mediaEntity->getName() ) {
			$mediaEntity->updateName( $name );
		}

		if ( $mediaDTO->description !== $mediaEntity->getDescription() ) {
			$mediaEntity->updateDescription( $mediaDTO->description );
		}

		if ( isset( $file ) && $file !== $mediaEntity->getFile() ) {
			$mediaEntity->updateFile( $file );
		}

		return $mediaEntity;
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

}