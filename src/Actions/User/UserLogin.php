<?php


namespace App\Actions\User;

use App\Responders\ViewResponders;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/log-in", name="user_login")
 * Class UserLogin
 * @package App\Actions\User
 */
class UserLogin {
	public function __invoke( ViewResponders $viewResponders ): Response {
		return $viewResponders( 'core/login.html.twig' );
	}
}