<?php

namespace App\Domain\Media\Handlers;


use App\Domain\Media\ImageDTO;
use App\Domain\Media\VideoDTO;
use App\Entity\Media;
use Doctrine\ORM\EntityManagerInterface;

class MediaHandler {

	/** @var EntityManagerInterface */
	private EntityManagerInterface $entityManager;


	public function __construct( EntityManagerInterface $entityManager ) {
		$this->entityManager = $entityManager;
	}


	/**
	 * @param ImageDTO|VideoDTO $mediaDto
	 * @param string $fileWithExtension
	 * @param $fileType
	 *
	 * @return Media
	 */
	public function generateMediaEntity( $mediaDto, string $fileWithExtension, $fileType ): Media {

		$newMedia = Media::createMedia( $mediaDto, $fileWithExtension, $fileType );
		$this->entityManager->persist( $newMedia );
		$this->entityManager->flush();

		return $newMedia;
	}

	/**
	 * @param Media $mediaEntity
	 */
	public function delete( Media $mediaEntity ) {
		$this->entityManager->remove( $mediaEntity );
		$this->entityManager->flush();
	}

}