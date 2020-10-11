<?php


namespace App\Controller;



use App\Repository\TricksRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

class CommonController {

	private $templating;
	/**
	 * @var TricksRepository
	 */
	private $trick;

	public function __construct(Environment $templating, TricksRepository $trick) {
		$this->templating = $templating;
		$this->trick = $trick;
	}

	/**
	 * @Route("/")
	 * @return Response
	 * @throws \Exception
	 */
	public function index(): Response
	{
		$number = random_int(0, 100);


		return new Response(
			$this->templating->render('core/index.html.twig', ['number' => $number])
		);
	}
}