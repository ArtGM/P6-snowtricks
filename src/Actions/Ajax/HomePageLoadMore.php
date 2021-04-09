<?php


namespace App\Actions\Ajax;

use App\Entity\Trick;
use App\Responders\JsonResponders;
use App\Responders\RedirectResponders;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

/**
 * Class HomePageLoadMore
 * @package App\Actions\Ajax
 * @Route ( "/load-more-tricks/page/{page}", name="load-more-tricks")
 */
class HomePageLoadMore {

	/**
	 * @var Environment
	 */
	private Environment $templating;

	/** @var EntityManagerInterface */
	private EntityManagerInterface $entityManager;

	/**
	 * HomePageLoadMore constructor.
	 *
	 * @param Environment $templating
	 * @param EntityManagerInterface $entityManager
	 */
	public function __construct( Environment $templating, EntityManagerInterface $entityManager ) {
		$this->templating    = $templating;
		$this->entityManager = $entityManager;
	}

	/**
	 * @param Request $request
	 * @param JsonResponders $jsonResponders
	 * @param RedirectResponders $redirectResponders
	 * @param int $page
	 *
	 * @return Response
	 * @throws LoaderError
	 * @throws RuntimeError
	 * @throws SyntaxError
	 */
	public function __invoke(
		Request $request,
		JsonResponders $jsonResponders,
		RedirectResponders $redirectResponders,
		int $page = 1
	): Response {

		if ( (bool) ! $request->headers->get( "snow-request" ) ) {
			return new Response( 'Forbidden Access', Response::HTTP_FORBIDDEN );
		}

		$offset           = $page * 4;
		$tricksRepository = $this->entityManager->getRepository( Trick::class );
		$getOtherTricks   = $tricksRepository->findBy( [], [], 4, $offset );
		$html             = '';

		foreach ( $getOtherTricks as $trickData ) {
			$html .= $this->templating->render( 'components/trick_miniature.html.twig', [
				'trick' => $trickData
			] );
		}
		$remainingTricks = count( $getOtherTricks );
		$message         = $remainingTricks < 4 ? 'That\'s all folks !' : '';


		return $jsonResponders( [
			'html'    => $html,
			'message' => $message,
		] );

	}
}
