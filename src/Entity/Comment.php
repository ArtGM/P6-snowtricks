<?php


namespace App\Entity;

use App\Repository\CommentRepository;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\OneToOne;

/**
 * @ORM\Table (name="st_comment")
 * @ORM\Entity (repositoryClass=CommentRepository::class)
 */
class Comment {
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
	 * @ORM\Column (type="text")
	 */
	private $content;

	/**
	 * @var DateTime
	 *
	 * @ORM\Column (type="datetime")
	 */
	private $date;

	/**
	 * @var int
	 * One Comment belong to One Trick
	 * @OneToOne (targetEntity="App\Entity\Tricks")
	 */
	private $trick;

	/**
	 * @var int
	 * One Comment Belong to One User
	 * @OneToOne (targetEntity="App\Entity\User")
	 */
	private $user;

	/**
	 * @return int
	 */
	public function get_id(): int {
		return $this->id;
	}

	/**
	 * @return string
	 */
	public function get_content(): string {
		return $this->content;
	}

	/**
	 * @return DateTime
	 */
	public function get_date(): DateTime {
		return $this->date;
	}

	/**
	 * @return int
	 */
	public function get_trick(): int {
		return $this->trick;
	}

	/**
	 * @return int
	 */
	public function get_user(): int {
		return $this->user;
	}
}