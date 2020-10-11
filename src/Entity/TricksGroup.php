<?php


namespace App\Entity;

use App\Repository\TricksGroupRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table (name="st_tricks_group")
 * @ORM\Entity (repositoryClass=TricksGroupRepository::class)
 */
class TricksGroup {
	/**
	 * @var string
	 *
	 * @ORM\Id()
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
	private $description;

	/**
	 * @return string
	 */
	public function get_id(): string {
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

}