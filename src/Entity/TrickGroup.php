<?php


namespace App\Entity;

use App\Repository\TricksGroupRepository;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\UuidInterface;
use Ramsey\Uuid\Doctrine\UuidGenerator;

/**
 * @ORM\Table (name="st_trick_group")
 * @ORM\Entity (repositoryClass=TricksGroupRepository::class)
 */
class TrickGroup {
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
	 * @return string
	 */
	public function getName(): string {
		return $this->name;
	}

	/**
	 * @var string
	 *
	 * @ORM\Column (type="text")
	 */
	private string $description;

	/**
	 * @var DateTime
	 *
	 * @ORM\Column (type="datetime")
	 */
	private DateTime $created_at;

	public function __construct( string $name, string $description ) {
		$this->name        = $name;
		$this->description = $description;
		$this->created_at  = new DateTime();
	}

}
