<?php


namespace App\Domain\Media;

use App\Domain\Media\Interfaces\MediaDTOInterface;
use Symfony\Component\Validator\Constraints as Assert;

class VideoDTO implements MediaDTOInterface {

	/**
	 * @var string|null
	 */
	public ?string $id = null;

	/**
	 * @var string|null
	 */
	public ?string $title;

	/**
	 * @var string|null
	 */
	public ?string $description;


	/**
	 * @var string|null
	 * @Assert\Url()
	 */
	public ?string $url;

}
