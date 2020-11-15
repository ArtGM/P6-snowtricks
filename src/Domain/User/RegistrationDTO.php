<?php


namespace App\Domain\User;

use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class RegistrationDTO
 * @package App\Domain\User
 */
class RegistrationDTO {
	/**
	 * @var string|null
	 *
	 * @Assert\NotBlank (message="le nom est requis")
	 * @Assert\Length(
	 *      min = 3,
	 *      max = 16,
	 *      minMessage = "Your name must be at least {{ limit }} characters long",
	 *      maxMessage = "Your name cannot be longer than {{ limit }} characters",
	 *      allowEmptyString = false
	 * )
	 */
	public ?string $name;

	/**
	 * @var string|null
	 * @Assert\NotBlank (message="le nom est requis")
	 * @Assert\Email(
	 *     message="please provide a valid email"
	 * )
	 */
	public ?string $email;

	/**
	 * @var string|null
	 * @Assert\Length(
	 *      min = 8,
	 *      max = 16,
	 *      minMessage = "Your password name must be at least {{ limit }} characters long",
	 *      maxMessage = "Your password name cannot be longer than {{ limit }} characters",
	 *      allowEmptyString = false
	 * )
	 *
	 */
	public ?string $password;
}