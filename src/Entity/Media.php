<?php


namespace App\Entity;

use App\Repository\MediaRepository;
use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table (name="st_media")
 * @ORM\Entity (repositoryClass=MediaRepository::class)
 */
class Media {

	/**
	 * @var int
	 *
	 * @ORM\Id()
	 * @ORM\Column (type="integer")
	 */
	private $id;

	/**
	 * @var string
	 *
	 * @ORM\Column (type="string")
	 */
	private $name;

	/**
	 * @var string
	 *
	 * @ORM\Column (type="text")
	 */
	private $description;

	/**
	 * @var string
	 *
	 * @ORM\Column (type="string")
	 */
	private $file;

	/**
	 * @var string
	 *
	 * @ORM\Column (type="string")
	 */
	private $type;

	/**
	 * @var DateTime
	 *
	 * @ORM\Column (type="datetime")
	 */
	private $created_at;


	/**
	 * @return int
	 */
	public function get_id(): int {
		return $this->id;
	}

	/**
	 * @return string
	 */
	public function get_name(): string {
		return $this->name;
	}

	/**
	 * @return string
	 */
	public function get_description(): string {
		return $this->description;
	}

	/**
	 * @return string
	 */
	public function get_file(): string {
		return $this->file;
	}

	/**
	 * @return string
	 */
	public function get_type(): string {
		return $this->type;
	}

}