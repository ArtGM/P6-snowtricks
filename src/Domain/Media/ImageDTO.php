<?php


namespace App\Domain\Media;


use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

class ImageDTO {

	/**
	 * @var string|null
	 */
	public ?string $id = null;

	/**
	 * @var string|null
	 */
	public ?string $name;

	/**
	 * @var string|null
	 */
	public ?string $description;

	/**
	 * @var UploadedFile|string|null
	 * @Assert\Image()
	 * @Assert\NotBlank (
	 *     message="avatar required",
	 *     groups={"avatar"}
	 * )
	 */
	public ?UploadedFile $file;

}