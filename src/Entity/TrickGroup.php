<?php


namespace App\Entity;

use App\Repository\TricksGroupRepository;
use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table (name="st_trick_group")
 * @ORM\Entity (repositoryClass=TricksGroupRepository::class)
 */
class TrickGroup {
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

	/**
	 * @var DateTime
	 *
	 * @ORM\Column (type="datetime")
	 */
	private $created_at;



}