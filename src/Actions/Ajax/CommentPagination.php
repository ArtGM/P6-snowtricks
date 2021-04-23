<?php


namespace App\Actions\Ajax;

use App\Entity\Comment;
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
 * Class CommentPagination
 * @package App\Actions\Ajax
 * @Route ( "/load-more-comment/page/{page}", name="load-more-comments")
 */
class CommentPagination {
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

		$offset            = $page * 10;
		$commentRepository = $this->entityManager->getRepository( Comment::class );
		$getOtherComments  = $commentRepository->findBy( [], [], 10, $offset );
		$output            = '';

		foreach ( $getOtherComments as $commentData ) {
			$output .= $this->templating->render( 'components/comment.html.twig', [
				'comment' => $commentData
			] );
		}
		$remainingComment = count( $getOtherComments );
		$message          = $remainingComment < 10 ? 'No more Comments' : '';


		return $jsonResponders( [
			'html'    => $output,
			'message' => $message,
		] );

	}
}
