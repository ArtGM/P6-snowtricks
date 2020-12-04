<?php


namespace App\Actions\Ajax;

use App\Entity\Trick;
use App\Responders\JsonResponders;
use App\Responders\ViewResponders;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

/**
 * Class HomePageLoadMore
 * @package App\Actions\Ajax
 * @Route ( "/load-more-tricks", name="load-more-tricks")
 */
class HomePageLoadMore {

	/**
	 * @var Environment
	 */
	private Environment $templating;

	public function __construct( Environment $templating ) {
		$this->templating = $templating;
	}

	public function __invoke( Request $request, JsonResponders $jsonResponders, EntityManagerInterface $entityManager ): Response {

		$tricksRepository = $entityManager->getRepository( Trick::class );
		$getOtherTricks   = $tricksRepository->findBy( [], [], 4, 4 );
		$html             = '';
		foreach ( $getOtherTricks as $trickData ) {
			$html .= $this->templating->render( 'components/trick_miniature.html.twig', [
				'trick' => $trickData
			] );
		}

		return $jsonResponders( [
			'html' => $html,
		] );

	}
}