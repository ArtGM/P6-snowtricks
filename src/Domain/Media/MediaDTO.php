<?php


namespace App\Domain\Media;


use App\Entity\Media;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

class MediaDTO {
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
	 */
	public ?UploadedFile $file;

}