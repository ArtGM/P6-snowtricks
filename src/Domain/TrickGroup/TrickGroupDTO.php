<?php


namespace App\Domain\TrickGroup;


use Symfony\Component\HttpFoundation\File\UploadedFile;

class TrickGroupDTO {

	/**
	 * @var string|null
	 */
	public ?string $name;

	/**
	 * @var string
	 */
	public string $description;


}
