<?php

namespace App\Domain\Factory;


use App\Domain\Trick\TrickDTO;
use App\Entity\Trick;

/**
 * Class TrickDtoFactory
 * @package App\Domain\Factory
 */
final class TrickDtoFactory {
	/** @var ImageDtoFactory */
	private ImageDtoFactory $mediaToDto;


	/**
	 * TrickDTOFactory constructor.
	 *
	 * @param ImageDtoFactory $mediaToDto
	 */
	public function __construct( ImageDtoFactory $mediaToDto ) {
		$this->mediaToDto = $mediaToDto;

	}

	/**
	 * @param Trick $trick
	 *
	 * @return TrickDTO
	 */
	public function create( Trick $trick ): TrickDTO {
		$trickDto = new TrickDTO();


		$imagesCollection = $trick->getMedias();
		$imagesDto        = [];

		foreach ( $imagesCollection->getValues() as $imageEntity ) {
			$imagesDto[] = $this->mediaToDto->create( $imageEntity );
		}
		$trickDto->images = $imagesDto;

		$trickDto->name        = $trick->getName();
		$trickDto->trickGroup  = $trick->getTricksGroup();
		$trickDto->description = $trick->getDescription();
		$trickDto->images      = $imagesDto;

		return $trickDto;
	}
}