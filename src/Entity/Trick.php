<?php

namespace App\Entity;

use App\Domain\Trick\TrickDTO;
use App\Repository\TricksRepository;
use DateTime;
use Symfony\Component\String\AbstractUnicodeString;
use Symfony\Component\String\Slugger\AsciiSlugger;
use Symfony\Component\String\Slugger\SluggerInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\UuidInterface;
use Ramsey\Uuid\Doctrine\UuidGenerator;

/**
 * @ORM\Table(name="st_trick")
 * @ORM\Entity(repositoryClass=TricksRepository::class)
 */
class Trick {
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
	 * @ORM\Column(type="string", length=255)
	 */
	protected string $name;


	/**
	 * @var string
	 *
	 * @ORM\Column(type="text", nullable=true)
	 */
	private string $description;

	/**
	 *
	 * @var TrickGroup
	 * Many Trick have one Trick_group
	 * @ORM\ManyToOne(targetEntity="App\Entity\TrickGroup")
	 */
	private TrickGroup $tricks_group;

	/**
	 * Many tricks have Many medias
	 *
	 * @ORM\ManyToMany(targetEntity="App\Entity\Media")
	 * @ORM\JoinTable(name="trick_has_media",
	 *      joinColumns={@ORM\JoinColumn(name="trick_id", referencedColumnName="id")},
	 *      inverseJoinColumns={@ORM\JoinColumn(name="media_id", referencedColumnName="id")}
	 *     )
	 */
	private Collection $medias;

	/**
	 * @var DateTime
	 *
	 * @ORM\Column (type="datetime")
	 */
	private DateTime $created_at;

	/**
	 * @var DateTime
	 *
	 * @ORM\Column (type="datetime")
	 */
	private DateTime $updated_at;

	/**
	 * @var string
	 *
	 * @ORM\Column(type="string", length=255, nullable=true)
	 */
	private string $slug;

	public function __construct( string $name, string $description, TrickGroup $trickGroup, ArrayCollection $medias ) {
		$this->name         = $name;
		$this->description  = $description;
		$this->created_at   = new DateTime();
		$this->updated_at   = new DateTime();
		$this->tricks_group = $trickGroup;
		$this->medias       = $medias;
	}

	public static function createFromDto( TrickDTO $trickDto, array $mediaEntity ): Trick {
		$medias = new ArrayCollection( $mediaEntity );

		return new self( $trickDto->name, $trickDto->description, $trickDto->trickGroup, $medias );
	}

	/**
	 * @param TrickDTO $trickDTO
	 * @param array $mediaEntity
	 *
	 * @return $this
	 */
	public function update( TrickDTO $trickDTO, array $mediaEntity ): Trick {
		$this->name         = (string) $trickDTO->name;
		$this->description  = (string) $trickDTO->description;
		$this->tricks_group = $trickDTO->trickGroup;
		$this->updated_at   = new DateTime();
		$this->medias       = new ArrayCollection( $mediaEntity );

		return $this;
	}


	public function get_slug(): string {
		$slugger    = new AsciiSlugger();
		$this->slug = strtolower( $slugger->slug( $this->name ) );

		return $this->slug;
	}

	/**
	 * @return UuidInterface
	 */
	public function get_id(): UuidInterface {
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
	public function get_description(): string {
		return $this->description;
	}

	/**
	 * @return TrickGroup
	 */
	public function get_tricks_group(): TrickGroup {
		return $this->tricks_group;
	}

	/**
	 * @return ArrayCollection|Collection
	 */
	public function get_medias() {
		return $this->medias;
	}

	/**
	 * @return DateTime
	 */
	public function get_created_at(): DateTime {
		return $this->created_at;
	}

	/**
	 * @return DateTime
	 */
	public function get_updated_at(): DateTime {
		return $this->updated_at;
	}
}
