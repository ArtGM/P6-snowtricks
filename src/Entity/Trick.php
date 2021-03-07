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
	 * @var string
	 *
	 * @ORM\Column(type="string", length=255)
	 */
	protected string $name;
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
	 * @ORM\ManyToMany(
	 *     targetEntity="App\Entity\Media",
	 *     cascade={"persist", "remove"}
	 *     )
	 * @ORM\JoinTable(
	 *      name="trick_has_media",
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
		$slugger = new AsciiSlugger();

		$this->name         = $name;
		$this->description  = $description;
		$this->created_at   = new DateTime();
		$this->updated_at   = new DateTime();
		$this->tricks_group = $trickGroup;
		$this->medias       = $medias;
		$this->slug         = strtolower( $slugger->slug( $name ) );
	}

	public static function createFromDto( TrickDTO $trickDto, array $mediaEntity ): Trick {
		$medias = new ArrayCollection( $mediaEntity );

		return new self( $trickDto->name, $trickDto->description, $trickDto->trickGroup, $medias );
	}


	/**
	 * @param Media $media
	 */
	public function removeMedia($media ) {
		if ( ! $this->medias->contains( $media ) || !isset($media)  ) {
			return;
		}
		$this->medias->removeElement( $media );
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

	/**
	 * @return UuidInterface
	 */
	public function getId(): UuidInterface {
		return $this->id;
	}

	/**
	 * @return string
	 */
	public function getSlug(): string {
		return $this->slug;
	}

	/**
	 * @return string
	 */
	public function getName(): string {
		return $this->name;
	}

	/**
	 * @return string
	 */
	public function getDescription(): string {
		return $this->description;
	}

	/**
	 * @return TrickGroup
	 */
	public function getTricksGroup(): TrickGroup {
		return $this->tricks_group;
	}

	/**
	 * @return ArrayCollection|Collection
	 */
	public function getMedias() {
		return $this->medias;
	}

	/**
	 * @return DateTime
	 */
	public function getCreatedAt(): DateTime {
		return $this->created_at;
	}

	/**
	 * @return DateTime
	 */
	public function getUpdatedAt(): DateTime {
		return $this->updated_at;
	}
}
