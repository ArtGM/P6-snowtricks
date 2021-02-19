<?php


namespace App\Domain\Media;


use App\Entity\Media;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

class ImageDTO {

	/**
	 * @var string|null
	 */
	public ?string $id;

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