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
	private DateTime $created_at;

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
	private string $userId;

	/**
	 * TokenHistory constructor.
	 *
	 * @param string $type
	 * @param string $userId
	 */
	public function __construct( string $type, string $userId ) {
		$this->created_at = new DateTime();
		$this->value      = md5( uniqid() );
		$this->type       = $type;
		$this->userId     = $userId;
	}

	/**
	 * @param string $type
	 * @param string $userId
	 *
	 * @return TokenHistory
	 */
	public static function createToken( string $type, string $userId ): TokenHistory {
		return new self( $type, $userId );
	}

	public function getValue() {
		return $this->getValue();
	}

}