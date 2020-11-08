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
	public $name;

	/**
	 * @var string|null
	 *
	 * @Assert\NotBlank (
	 *     message="la description est requise"
	 * )
	 */
	public $description;

}