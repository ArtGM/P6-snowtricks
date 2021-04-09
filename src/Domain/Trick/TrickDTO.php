<?php


namespace App\Domain\Trick;


use App\Entity\TrickGroup;
use App\Validator\Constraints\UniqueEntityDto;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * Class TrickDTO
 * @package App\Domain\Trick
 * @UniqueEntityDto(
 *     fieldMapping={"name": "name"},
 *     entityClass="App\Entity\Trick",
 *     message="Trick already Registered",
 *     groups={"create"}
 * )
 */
class TrickDTO {

	/**
	 * @var string|null
	 * @Assert\NotBlank (
	 *     message="name is required",
	 *     groups={"create", "update"}
	 * )
	 */
	public ?string $name;

	/**
	 * @var string|null
	 * @Assert\NotBlank (
	 *     message="description is required",
	 *     groups={"create", "update"}
	 * )
	 */
	public ?string $description;

	/**
	 * @var array
	 */
	public array $images;

	/**
	 * @var array
	 */
	public array $video;

	/**
	 * @var TrickGroup
	 *
	 */
	public TrickGroup $trickGroup;


}
