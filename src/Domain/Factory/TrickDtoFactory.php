<?php

namespace App\Domain\Factory;


use App\Domain\Trick\Form\TrickDTO;
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


		$mediaCollection = $trick->getMedias();
		$imagesDto       = [];
		$videoDto        = [];

		foreach ( $mediaCollection->getValues() as $mediaEntity ) {
			if ( $mediaEntity->getType() === 'image/jpeg' ) {
				$imagesDto[] = $this->mediaToDto->createImage( $mediaEntity );
			}
			if ( $mediaEntity->getType() === 'video' ) {
				$videoDto[] = $this->mediaToDto->createVideo( $mediaEntity );
			}
		}


		$trickDto->name        = $trick->getName();
		$trickDto->trickGroup  = $trick->getTricksGroup();
		$trickDto->description = $trick->getDescription();
		$trickDto->video       = $videoDto;
		$trickDto->images      = $imagesDto;

		return $trickDto;
	}
}