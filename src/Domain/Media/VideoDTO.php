<?php


namespace App\Domain\Media;

use Symfony\Component\Validator\Constraints as Assert;

class VideoDTO {

	/**
	 * @var string|null
	 */
	public ?string $id = null;

	/**
	 * @var string
	 */
	public string $title;

	/**
	 * @var string
	 */
	public string $description;


	/**
	 * @var string|null
	 * @Assert\Url()
	 */
	public ?string $url;

}