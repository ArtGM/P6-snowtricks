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
	private string $id;

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
	private string $description;

	/**
	 * @var DateTime
	 *
	 * @ORM\Column (type="datetime")
	 */
	private DateTime $created_at;



}