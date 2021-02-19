<?php


namespace App\Domain\User\Profile;


use App\Domain\Media\ImageDTO;
use App\Entity\Media;
use Symfony\Component\Validator\Constraints as Assert;

class UserProfileDTO {
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
	public ?string $username;

	/**
	 * @var string|null
	 * @Assert\NotBlank (message="le 'email est requis")
	 * @Assert\Email(
	 *     message="please provide a valid email"
	 * )
	 */
	public ?string $email;

	/**
	 * @var ImageDTO
	 */
	public ImageDTO $avatar;

}