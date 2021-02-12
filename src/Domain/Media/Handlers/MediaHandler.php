<?php

namespace App\Domain\Media\Handlers;


use App\Domain\Media\ImageDTO;
use App\Domain\Media\VideoDTO;
use App\Entity\Media;
use App\Service\FileUploader;
use Doctrine\ORM\EntityManagerInterface;

class MediaHandler {

	/** @var EntityManagerInterface */
	private EntityManagerInterface $entityManager;

	/** @var FileUploader */
	private FileUploader $fileUploader;


	public function __construct( EntityManagerInterface $entityManager, FileUploader $fileUploader ) {
		$this->entityManager = $entityManager;
		$this->fileUploader  = $fileUploader;
	}


	/**
	 * @param ImageDTO|VideoDTO $mediaDto
	 * @param string $fileOrYoutubeVideoId
	 * @param $fileType
	 *
	 * @return Media
	 */
	private function generateMediaEntity( $mediaDto, string $fileOrYoutubeVideoId, $fileType ): Media {

		$newMedia = Media::createMedia( $mediaDto, $fileOrYoutubeVideoId, $fileType );
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

	/**
	 * @param $url
	 *
	 * @return mixed
	 */
	private function getYoutubeIdFromUrl( $url ) {
		$parts = parse_url( $url );
		parse_str( $parts['query'], $query );

		return $query['v'];
	}

	/**
	 * @param ImageDTO $imageDto
	 *
	 * @return Media
	 */
	public function generateImage( ImageDTO $imageDto ): Media {
		$fileType = $imageDto->file->getClientMimeType();
		$file     = $this->fileUploader->upload( $imageDto->file );

		return $this->generateMediaEntity( $imageDto, $file, $fileType );
	}

	/**
	 * @param VideoDTO $videoDto
	 *
	 * @return Media
	 */
	public function generateVideo( VideoDTO $videoDto ): Media {
		$youtubeVideoId = $this->getYoutubeIdFromUrl( $videoDto->url );

		return $this->generateMediaEntity( $videoDto, $youtubeVideoId, 'video' );
	}

}