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
	 * @param FormInterface $trickEditionForm
	 * @param Trick $trick
	 */
	public function handle( FormInterface $trickEditionForm, Trick $trick ) {

		/** @var TrickDTO $trickDto */
		$trickDto = $trickEditionForm->getData();

		$modifiedMedias = $this->replaceMediasFromDto( array_merge( $trickDto->images, $trickDto->video ), $trick );
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


		$i                   = 0;
		$j                   = 0;
		$medias              = $trick->getMedias()->getValues();
		$mediasToAddOrUpdate = [];
		while ( $i < count( $dto ) || $j < count( $medias ) ) {

			/** @var ImageDTO|VideoDTO $mediaDTO */
			$mediaDTO = $dto[ $i ];

			/** @var Media $mediaEntity */
			$mediaEntity = isset( $medias[ $j ] ) ? $medias[ $j ] : null;

			if ( $this->isNewImage( $mediaDTO ) ) {

				$mediasToAddOrUpdate[] = $this->mediaCreation->generateImage( $mediaDTO );

			} elseif ( $this->isNewVideo( $mediaDTO ) ) {

				$mediasToAddOrUpdate[] = $this->mediaCreation->generateVideo( $mediaDTO );

			} elseif ( $this->mediaAlreadyExist( $mediaDTO, $mediaEntity ) ) {

				$mediasToAddOrUpdate[] = $this->updateMediaEntity( $mediaEntity, $mediaDTO );

				$j ++;

			} else {

				$trick->removeMedia( $mediaEntity );

				$j ++;
			}

			$i ++;
		}

		return $mediasToAddOrUpdate;

	}

	/**
	 * @param $mediaDTO
	 *
	 * @return bool
	 */
	private function isNewImage( $mediaDTO ): bool {
		$reflectionPropertyImage = new ReflectionProperty( 'App\Domain\Media\ImageDTO', 'id' );

		return $mediaDTO instanceof ImageDTO && ! $reflectionPropertyImage->isInitialized( $mediaDTO );
	}

	/**
	 * @param $mediaDTO
	 *
	 * @return bool
	 */
	private function isNewVideo( $mediaDTO ): bool {
		$reflectionPropertyVideo = new ReflectionProperty( 'App\Domain\Media\VideoDTO', 'id' );

		return $mediaDTO instanceof VideoDTO && ! $reflectionPropertyVideo->isInitialized( $mediaDTO );
	}

	/**
	 * @param $mediaDTO
	 * @param $mediaEntity
	 *
	 * @return bool
	 */
	private function mediaAlreadyExist( $mediaDTO, $mediaEntity ): bool {
		if (isset($mediaEntity)) {
		return $mediaDTO->id === (string) $mediaEntity->getId();
		}
		return false;
	}

	/**
	 * @param $mediaEntity
	 * @param $mediaDTO
	 *
	 * @return mixed
	 */
	private function updateMediaEntity( $mediaEntity, $mediaDTO ): Media {

		$name = $mediaEntity->getType() === 'video' ? $mediaDTO->title : $mediaDTO->name;

		if ( isset( $mediaEntity ) && $name !== $mediaEntity->getName() ) {
			$mediaEntity->updateName( $name );
		}
		if ( $mediaDTO->description !== $mediaEntity->getDescription() ) {
			$mediaEntity->updateDescription( $mediaDTO->description );
		}

		return $mediaEntity;
	}


}