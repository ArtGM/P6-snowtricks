<?php


namespace App\Domain\User\Password;


use Symfony\Component\Validator\Constraints as Assert;

class UserResetPasswordDTO {
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
