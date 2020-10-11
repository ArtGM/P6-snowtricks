<?php


namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\OneToOne;

/**
 * @ORM\Table (name="st_user")
 * @ORM\Entity (repositoryClass=UserRepository::class)
 */
class User {

	/**
	 * @var int
	 *
	 * @ORM\Id ()
	 * @ORM\Column (type="integer")
	 */
	private $id;

	/**
	 * @var string
	 *
	 * @ORM\Column (type="string")
	 */
	private $name;

	/**
	 * @var string
	 *
	 * @ORM\Column (type="text")
	 */
	private $email;

	/**
	 * @var string
	 *
	 * @ORM\Column (type="string")
	 */
	private $password;

	/**
	 * @var bool
	 *
	 * @ORM\Column (type="boolean")
	 */
	private $connected;

	/**
	 * One user has one avatar
	 * @OneToOne(targetEntity="App\Entity\Media")
	 */
	private $avatar;

	/**
	 * @ORM\ManyToOne (targetEntity="App\Entity\Tricks", inversedBy="contributors")
	 */
	private $contributions;

	/**
	 * @return mixed
	 */
	public function get_contributions() {
		return $this->contributions;
	}

	/**
	 * @return int
	 */
	public function get_id(): int {
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
	public function get_email(): string {
		return $this->email;
	}

	/**
	 * @return string
	 */
	public function get_password(): string {
		return $this->password;
	}

	/**
	 * @return bool
	 */
	public function is_connected(): bool {
		return $this->connected;
	}

	/**
	 * @return int
	 */
	public function get_avatar(): int {
		return $this->avatar;
	}

}