<?php


namespace App\Domain\Factory;


use App\Domain\Media\ImageDTO;
use App\Entity\Media;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ImageDtoFactory {

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
	 * @return ImageDTO
	 */
	public function create( Media $media ): ImageDTO {
		$uploadedFile          = new UploadedFile( $this->uploadDir . '/' . $media->getFile(), $media->getFile() );
		$mediaDto              = new ImageDTO();
		$mediaDto->id          = $media->getId();
		$mediaDto->name        = $media->getName();
		$mediaDto->description = $media->getDescription();
		$mediaDto->file        = $uploadedFile;

		return $mediaDto;
	}

}