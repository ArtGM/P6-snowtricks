<?php

namespace App\Actions\Media;


use App\Domain\Media\MediaDTO;
use App\Entity\Media;
use Doctrine\ORM\EntityManagerInterface;

class MediaCreation {

	/** @var EntityManagerInterface */
	private EntityManagerInterface $entityManager;

	public function __construct( EntityManagerInterface $entityManager ) {
		$this->entityManager = $entityManager;
	}


	/**
	 * @param MediaDTO $mediaDto
	 * @param string $fileWithExtension
	 * @param $fileType
	 *
	 * @return Media
	 */
	public function generateMediaEntity( MediaDTO $mediaDto, string $fileWithExtension, $fileType ): Media {
		$newMedia = Media::createMedia( $mediaDto, $fileWithExtension, $fileType );
		$this->entityManager->persist( $newMedia );
		$this->entityManager->flush();

		return $newMedia;
	}

}