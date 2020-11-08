<?php


namespace App\Actions\Trick;

use App\Responders\ViewResponders;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route ("/trick-form", name="trick-form)
 * Class TrickActions
 * @package App\Actions\Trick
 */
class TrickActions {
	public function __construct() {

	}

	public function __invoke(Request $request, ViewResponders $view_responders) {
		//TODO: add tricksform
	}
}