<?php

namespace App\Domain\Factory;


use App\Domain\Trick\TrickDTO;
use App\Entity\Trick;

/**
 * Class TrickDtoFactory
 * @package App\Domain\Factory
 */
final class TrickDtoFactory {
	/** @var MediaDtoFactory */
	private MediaDtoFactory $mediaToDto;


	/**
	 * TrickDTOFactory constructor.
	 *
	 * @param MediaDtoFactory $mediaToDto
	 */
	public function __construct( MediaDtoFactory $mediaToDto ) {
		$this->mediaToDto = $mediaToDto;

	}

	/**
	 * @param Trick $trick
	 *
	 * @return TrickDTO
	 */
	public function create( Trick $trick ): TrickDTO {
		$trickDto = new TrickDTO();


		$imagesCollection = $trick->get_medias();
		$imagesDto        = [];

		foreach ( $imagesCollection->getValues() as $imageEntity ) {
			$imagesDto[] = $this->mediaToDto->create( $imageEntity );
		}
		$trickDto->images = $imagesDto;

		$trickDto->name        = $trick->get_name();
		$trickDto->trickGroup  = $trick->get_tricks_group();
		$trickDto->description = $trick->get_description();
		$trickDto->images      = $imagesDto;

		return $trickDto;
	}
}