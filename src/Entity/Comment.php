<?php


namespace App\Entity;

use App\Domain\Comment\CommentDTO;
use App\Repository\CommentRepository;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\ManyToOne;
use Ramsey\Uuid\UuidInterface;
use Ramsey\Uuid\Doctrine\UuidGenerator;

/**
 * @ORM\Table (name="st_comment")
 * @ORM\Entity (repositoryClass=CommentRepository::class)
 */
class Comment {
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
	 * @ORM\Column (type="text")
	 */
	private string $content;

	/**
	 * @var DateTime
	 *
	 * @ORM\Column (type="datetime")
	 */
	private DateTime $created_at;

	/**
	 * @var Trick
	 * Many Comments have One trick
	 * @ManyToOne (targetEntity="App\Entity\Trick")
	 */
	private Trick $trick;

	/**
	 * @var User
	 * Many Comments have one user
	 * @ManyToOne (targetEntity="App\Entity\User")
	 */
	private User $user;


	/**
	 * Comment constructor.
	 *
	 * @param string $content
	 * @param Trick $trick
	 * @param User $user
	 */
	public function __construct( string $content, Trick $trick, User $user ) {
		$this->content    = $content;
		$this->trick      = $trick;
		$this->user       = $user;
		$this->created_at = new DateTime();

	}

	/**
	 * @param CommentDTO $commentDto
	 * @param Trick $trick
	 * @param User $user
	 *
	 * @return Comment
	 */
	public static function createFromDto( CommentDTO $commentDto, Trick $trick, User $user ): Comment {
		return new self( $commentDto->content, $trick, $user );
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
	public function getContent(): string {
		return $this->content;
	}

	/**
	 * @return string
	 */
	public function getCreatedAt(): string {
		return $this->created_at->format( 'd M Y' );
	}

	/**
	 * @return Trick
	 */
	public function getTrick(): Trick {
		return $this->trick;
	}

	/**
	 * @return User
	 */
	public function getUser(): User {
		return $this->user;
	}


}
