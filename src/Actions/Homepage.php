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
	 * @param Request $request
	 * @param ViewResponders $viewResponders
	 *
	 * @param EntityManagerInterface $entityManager
	 *
	 * @return Response
	 */
	public function __invoke( Request $request, ViewResponders $viewResponders, EntityManagerInterface $entityManager ): Response {

		$trickRepository = $entityManager->getRepository( Trick::class );
		$allTricks       = $trickRepository->findBy( [], [], 4 );
		return $viewResponders( 'core/index.html.twig', [
			'allTricks'   => $allTricks,
			'currentPage' => 1
		] );
	}

}
