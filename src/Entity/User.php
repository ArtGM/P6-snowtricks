<?php


namespace App\Entity;

use App\Domain\User\RegistrationDTO;
use App\Repository\UserRepository;
use DateTime;
use Doctrine\ORM\Mapping as ORM;

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
	private int $id;

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
	private string $email;

	/**
	 * @var string
	 *
	 * @ORM\Column (type="string")
	 */
	private string $password;

	/**
	 * @var string
	 *
	 * @ORM\Column (type="string")
	 */
	private string $roles;

	/**
	 * many user has one avatar
	 * @ORM\ManyToOne(targetEntity="App\Entity\Media")
	 */
	private string $avatar_id;

	/**
	 * Many Users have Many Trick
	 * @ORM\ManyToMany (targetEntity="App\Entity\Trick")
	 * @ORM\JoinTable(name="user_has_tricks",
	 *      joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
	 *      inverseJoinColumns={@ORM\JoinColumn(name="trick_id", referencedColumnName="id")}
	 *     )
	 */
	private $contributions;

	/**
	 * @var DateTime
	 *
	 * @ORM\Column (type="datetime")
	 */
	private DateTime $created_at;

	public function __construct(string $name, string $email, string $password) {
		$this->name = $name;
		$this->email = $email;
		$this->password = $password;
	}

	public static function createFromDto( RegistrationDTO $registrationDto ) : User {
		return new self($registrationDto->name, $registrationDto->email, $registrationDto->password);
	}

}