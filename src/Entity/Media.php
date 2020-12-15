<?php


namespace App\Entity;

use App\Domain\Media\MediaDTO;
use App\Repository\MediaRepository;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\UuidInterface;
use Ramsey\Uuid\Doctrine\UuidGenerator;

/**
 * @ORM\Table (name="st_media")
 * @ORM\Entity (repositoryClass=MediaRepository::class)
 */
class Media {

	/**
	 * @var UuidInterface
	 *
	 * @ORM\Id()
	 * @ORM\Column (type="uuid", unique=true)
	 * @ORM\GeneratedValue (strategy="CUSTOM")
	 * @ORM\CustomIdGenerator (class=UuidGenerator::class)
	 */
	private UuidInterface $id;

	/**
	 * @var string
	 *
	 * @ORM\Column (type="string")
	 */
	private string $name;

	/**
	 * @var string
	 *
	 * @ORM\Column (type="text")
	 */
	private string $description;

	/**
	 * @var string
	 *
	 * @ORM\Column (type="string")
	 */
	private string $file;

	/**
	 * @var string
	 *
	 * @ORM\Column (type="string")
	 */
	private string $type;

	/**
	 * @var DateTime
	 *
	 * @ORM\Column (type="datetime")
	 */
	private DateTime $created_at;

	public function __construct( string $name, string $description, string $file, string $type ) {
		$this->name        = $name;
		$this->description = $description;
		$this->type        = $type;
		$this->file        = $file;
		$this->created_at  = new DateTime();
	}

	public static function createMedia( MediaDTO $mediaDTO, $fileWithExtension, $type ): Media {
		return new self( $mediaDTO->name, $mediaDTO->description, $fileWithExtension, $type );
	}

	/**
	 * @return UuidInterface
	 */
	public function get_id(): UuidInterface {
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

	/**
	 * @return DateTime
	 */
	public function get_created_at(): DateTime {
		return $this->created_at;
	}


}