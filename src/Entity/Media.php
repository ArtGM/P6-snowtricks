<?php


namespace App\Entity;

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


}