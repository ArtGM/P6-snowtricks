<?php


namespace App\Domain\Factory;


use App\Domain\Media\MediaDTO;
use App\Entity\Media;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class MediaDtoFactory {

	/** @var string */
	private string $uploadDir;

	/**
	 * MediaDtoFactory constructor.
	 *
	 * @param string $uploadDir
	 */
	public function __construct( string $uploadDir ) {
		$this->uploadDir = $uploadDir;
	}

	/**
	 * @param Media $media
	 *
	 * @return MediaDTO
	 */
	public function create( Media $media ): MediaDTO {
		$uploadedFile          = new UploadedFile( $this->uploadDir . '/' . $media->get_file(), $media->get_file() );
		$mediaDto              = new MediaDTO();
		$mediaDto->name        = $media->get_name();
		$mediaDto->description = $media->get_description();
		$mediaDto->file        = $uploadedFile;

		return $mediaDto;
	}

}