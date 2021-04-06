<?php


namespace App\Actions;


use App\Entity\Trick;
use App\Responders\ViewResponders;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route ("/", name="homepage")
 * Class HomeAction
 * @package App\Actions\Home
 */
class Homepage {

	/**
	 * @var EntityManagerInterface
	 */
	private EntityManagerInterface $entityManager;

	public function __construct( EntityManagerInterface $entityManager ) {
		$this->entityManager = $entityManager;
	}

	/**
	 * @param Request $request
	 * @param ViewResponders $viewResponders
	 *
	 * @return Response
	 */
	public function __invoke( Request $request, ViewResponders $viewResponders ): Response {

		$trickRepository = $this->entityManager->getRepository( Trick::class );
		$allTricks       = $trickRepository->findBy( [], [], 4 );

		return $viewResponders( 'core/index.html.twig', [
			'allTricks'   => $allTricks,
			'currentPage' => 1
		] );
	}

}
