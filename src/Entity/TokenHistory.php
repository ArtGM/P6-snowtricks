<?php


namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\UuidInterface;
use Ramsey\Uuid\Doctrine\UuidGenerator;

/**
 * @ORM\Table (name="st_token_history")
 * @ORM\Entity (repositoryClass=TokenHistoryRepository::class)
 */
class TokenHistory {

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
	 * @var DateTime
	 *
	 * @ORM\Column (type="datetime")
	 */
	private DateTime $createdAt;

	/**
	 * @var string
	 *
	 * @ORM\Column (type="string")
	 */
	private string $value;

	/**
	 * @var string
	 *
	 * @ORM\Column (type="string")
	 */
	private string $type;

	/**
	 * Many tokens has one user
	 * @ORM\ManyToOne (targetEntity="App\Entity\User")
	 */
	private User $userId;

	/**
	 * TokenHistory constructor.
	 *
	 * @param string $type
	 * @param User $userId
	 */
	public function __construct( string $type, User $userId ) {
		$this->createdAt = new DateTime();
		$this->value     = md5( uniqid() );
		$this->type      = $type;
		$this->userId    = $userId;
	}

	/**
	 * @param string $type
	 * @param User $userId
	 *
	 * @return TokenHistory
	 */
	public static function createToken( string $type, User $userId ): TokenHistory {
		return new self( $type, $userId );
	}

	public function getValue(): string {
		return $this->value;
	}

	public function getCreatedAt(): DateTime {
		return $this->createdAt;
	}

	public function getUserId(): string {
		return $this->userId->getId();
	}

	public function getType(): string {
		return $this->type;
	}

}
