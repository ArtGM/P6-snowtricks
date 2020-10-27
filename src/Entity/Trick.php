<?php

namespace App\Entity;

use App\Repository\TricksRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\ManyToMany;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;

/**
 * @ORM\Table(name="st_trick")
 * @ORM\Entity(repositoryClass=TricksRepository::class)
 */
class Trick {
	/**
	 * @var int
	 *
	 * @ORM\Id()
	 * @ORM\Column(type="integer")
	 */
	protected $id;

	/**
	 * @var string
	 *
	 * @ORM\Column(type="string", length=255)
	 */
	private $name;

	/**
	 * @var string
	 *
	 * @ORM\Column(type="text", nullable=true)
	 */
	private $description;

	/**
	 *
	 * @var int
	 * Many Trick have one Trick_group
	 * @ManyToOne(targetEntity="App\Entity\TrickGroup")
	 */
	private $tricks_group;

	/**
	 * Many tricks have Many medias
	 *
	 * @ManyToMany(targetEntity="App\Entity\Media")
	 *  @ORM\JoinTable(name="trick_has_media",
	 *      joinColumns={@ORM\JoinColumn(name="trick_id", referencedColumnName="id")},
	 *      inverseJoinColumns={@ORM\JoinColumn(name="media_id", referencedColumnName="id")}
	 *     )
	 */
	private $medias;

	/**
	 * @return ArrayCollection
	 */
	public function get_medias(): ArrayCollection {
		return $this->medias;
	}


	public function __construct() {
		$this->medias       = new ArrayCollection();
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
	public function get_description(): string {
		return $this->description;
	}

	/**
	 * @return int
	 */
	public function get_tricks_group(): int {
		return $this->tricks_group;
	}


}
