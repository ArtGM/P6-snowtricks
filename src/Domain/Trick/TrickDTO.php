<?php


namespace App\Domain\Trick;


use App\Domain\TrickGroup\TrickGroupDTO;
use App\Entity\TrickGroup;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

class TrickDTO {

	/**
	 * @var string|null
	 * @Assert\NotBlank (
	 *     message="name is required"
	 * )
	 */
	public ?string $name;

	/**
	 * @var string|null
	 * @Assert\NotBlank (
	 *     message="description is required"
	 * )
	 */
	public ?string $description;

	/**
	 * @var array
	 */
	public array $images;

	/**
	 * @var TrickGroup
	 *
	 */
	public TrickGroup $trickGroup;

}