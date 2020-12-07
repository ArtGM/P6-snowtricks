<?php


namespace App\Actions;


use App\Responders\ViewResponders;
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
	 * @param ViewResponders $view_responders
	 *
	 * @return Response
	 */
	public function __invoke( Request $request, ViewResponders $view_responders ): Response {
		return $view_responders( 'core/index.html.twig' );
	}

}
