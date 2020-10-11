<?php

namespace App\Repository;

use App\Entity\Tricks;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;


class TricksRepository extends ServiceEntityRepository
{

	public function __construct( ManagerRegistry $registry) {
		parent::__construct( $registry, Tricks::class );
	}

}
