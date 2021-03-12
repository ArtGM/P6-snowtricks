<?php


namespace App\Domain\User\Password;


use Symfony\Component\Validator\Constraints as Assert;

class UserAskPasswordDTO {

	/**
	 * @var string
	 * ²@Assert\Email(
	 *     message="Please enter a valid email"
	 * )
	 * ²@Assert\NotBlank(
	 *     message="Please enter your email"
	 * )
	 */
	public string $email;
}