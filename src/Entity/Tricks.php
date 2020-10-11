<?php

namespace App\Entity;

use App\Repository\TricksRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\OneToOne;

/**
 * @ORM\Table(name="st_trick")
 * @ORM\Entity(repositoryClass=TricksRepository::class)
 */
class Tricks {
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
	 * One trick has One Tricks_group
	 * @OneToOne(targetEntity="App\Entity\TricksGroup")
	 */
	private $tricks_group;

	/**
	 * One trick have Many medias
	 *
	 * @OneToMany(targetEntity="App\Entity\Media", mappedBy="trick")
	 */
	private $medias;

	/**
	 * One trick has many contributors
	 *
	 * @OneToMany(targetEntity="App\Entity\User", mappedBy="contributions")
	 */
	private $contributors;

	/**
	 * @return ArrayCollection
	 */
	public function get_medias(): ArrayCollection {
		return $this->medias;
	}

	/**
	 * @return ArrayCollection
	 */
	public function get_contributors(): ArrayCollection {
		return $this->contributors;
	}

	public function __construct() {
		$this->medias       = new ArrayCollection();
		$this->contributors = new ArrayCollection();
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
