<?php


namespace App\Domain\Trick;

use Symfony\Bridge\Doctrine\Validator\Constraints as Assert;

class TrickDTO {

	/**
	 * @var string|null
	 *
	 * @Assert\NotBlank(
	 *     message="le nom est requis
	 * )
	 */
	public ?string $name;

	/**
	 * @var string|null
	 *
	 * @Assert\NotBlank (
	 *     message="la description est requise"
	 * )
	 */
	public ?string $description;

}