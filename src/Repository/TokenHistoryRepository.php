<?php


namespace App\Repository;


use App\Entity\TokenHistory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class TokenHistoryRepository extends ServiceEntityRepository {

	public function __construct( ManagerRegistry $registry ) {
		parent::__construct( $registry, TokenHistory::class );
	}

}