<?php


namespace App\Entity;

use App\Domain\Media\ImageDTO;
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

	/**
	 * Media constructor.
	 *
	 * @param string $name
	 * @param string $description
	 * @param string $file
	 * @param string $type
	 */
	public function __construct( string $name, string $description, string $file, string $type ) {
		$this->name        = $name;
		$this->description = $description;
		$this->type        = $type;
		$this->file        = $file;
		$this->created_at  = new DateTime();
	}

	/**
	 * @param ImageDTO $mediaDTO
	 * @param $fileWithExtension
	 * @param $type
	 *
	 * @return Media
	 */
	public static function createMedia( ImageDTO $mediaDTO, $fileWithExtension, $type ): Media {
		return new self( $mediaDTO->name, $mediaDTO->description, $fileWithExtension, $type );
	}

	/**
	 * @param string $name
	 */
	public function updateName( string $name ) {
		$this->name = $name;
	}

	/**
	 * @param string $description
	 */
	public function updateDescription( string $description ) {
		$this->description = $description;
	}

	/**
	 * @return UuidInterface
	 */
	public function getId(): UuidInterface {
		return $this->id;
	}

	/**
	 * @return string
	 */
	public function getName(): string {
		return $this->name;
	}

	/**
	 * @return string
	 */
	public function getDescription(): string {
		return $this->description;
	}

	/**
	 * @return string
	 */
	public function getFile(): string {
		return $this->file;
	}

	/**
	 * @return string
	 */
	public function getType(): string {
		return $this->type;
	}

	/**
	 * @return DateTime
	 */
	public function getCreatedAt(): DateTime {
		return $this->created_at;
	}


}