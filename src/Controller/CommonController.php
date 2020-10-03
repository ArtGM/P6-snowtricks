<?php


namespace App\Controller;



use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

class CommonController {

	protected $templating;

	public function __construct(Environment $templating) {
		$this->templating = $templating;
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