<?php


namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table (name="st_token_history")
 * @ORM\Entity (repositoryClass=TokenHistoryRepository::class)
 */
class TokenHistory {

	/**
	 * @var int
	 *
	 * @ORM\Id()
	 * @ORM\Column (type="integer")
	 */
	private int $id;

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
	private $user_id;

}