<?php


namespace App\Entity;

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
	 * @var int
	 * Many Comments have One trick
	 * @ManyToOne (targetEntity="App\Entity\Trick")
	 */
	private int $trick;

	/**
	 * @var int
	 * Many Comments have one user
	 * @ManyToOne (targetEntity="App\Entity\User")
	 */
	private int $user;

}