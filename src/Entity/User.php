<?php


namespace App\Entity;

use App\Domain\User\Registration\UserRegistrationDTO;
use App\Repository\UserRepository;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Doctrine\UuidGenerator;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Table (name="st_user")
 * @ORM\Entity (repositoryClass=UserRepository::class)
 */
class User implements UserInterface {

	/**
	 * @var UuidInterface
	 *
	 * @ORM\Id ()
	 * @ORM\Column (type="uuid", unique=true)
	 * @ORM\GeneratedValue(strategy="CUSTOM")
	 * @ORM\CustomIdGenerator(class=UuidGenerator::class)
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
	private string $email;

	/**
	 * @var string
	 *
	 * @ORM\Column (type="string")
	 */
	private string $password;

	/**
	 * @var array
	 *
	 * @ORM\Column (type="array")
	 */
	private array $roles;

	/**
	 * many user has one avatar
	 * @ORM\ManyToOne(targetEntity="App\Entity\Media")
	 * @ORM\JoinColumn(name="media_id", referencedColumnName="id", nullable=true)
	 */
	private ?string $avatarId;

	/**
	 * Many Users have Many Trick
	 * @ORM\ManyToMany (targetEntity="App\Entity\Trick")
	 * @ORM\JoinTable(name="user_has_tricks",
	 *      joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=true)},
	 *      inverseJoinColumns={@ORM\JoinColumn(name="trick_id", referencedColumnName="id", nullable=true)}
	 *     )
	 */
	private $contributions;

	/**
	 * @var DateTime
	 *
	 * @ORM\Column (type="datetime")
	 */
	private DateTime $created_at;

	/**
	 * User constructor.
	 *
	 * @param string $name
	 * @param string $email
	 * @param string $password
	 * @param array|string[] $roles
	 * @param string $avatarId
	 */
	public function __construct( string $name, string $email, string $password, string $avatarId = null, array $roles = [ 'ROLE_USER' ]) {
		$this->name       = $name;
		$this->email      = $email;
		$this->password   = $password;
		$this->created_at = new DateTime();
		$this->roles      = $roles;
		$this->avatarId = $avatarId;

	}

	/**
	 * @param UserRegistrationDTO $registrationDto
	 *
	 * @return User
	 */
	public static function createFromDto( UserRegistrationDTO $registrationDto ): User {
		return new self( $registrationDto->name, $registrationDto->email, $registrationDto->password );
	}

	/**
	 * @return string
	 */
	public function getId(): string {
		return $this->id->toString();
	}

	/**
	 * @return string
	 */
	public function getEmail(): string {
		return $this->email;
	}

	/**
	 * @return string
	 */
	public function getPassword(): string {
		return $this->password;
	}

	/**
	 * @return string
	 */
	public function getUsername(): string {
		return $this->name;
	}

	/**
	 * @return array|string[]
	 */
	public function getRoles(): array {
		return $this->roles;
	}

	public function getSalt(): ?string {
		return null;
	}

	public function eraseCredentials() {
		// TODO: Implement eraseCredentials() method.
	}

	/**
	 * @return string|null
	 */
	public function getAvatar(): ?string {
		return $this->avatarId;
	}
}