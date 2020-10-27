<?php


namespace App\Controller;



use App\Repository\TricksRepository;
use Exception;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

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
	 * @throws Exception
	 */
	public function index(): Response
	{
		//$number = random_int(0, 100);


		return new Response(
			$this->templating->render('core/index.html.twig')
		);
	}

	/**
	 * @Route ("test")
	 * @return Response
	 * @throws LoaderError
	 * @throws RuntimeError
	 * @throws SyntaxError
	 */
	public function test(): Response {
		return new Response(
			$this->templating->render('core/test.html.twig')
		);
	}
}