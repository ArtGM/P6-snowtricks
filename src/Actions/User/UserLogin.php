<?php


namespace App\Actions\User;

use App\Responders\ViewResponders;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

/**
 * @Route("/log-in", name="user_login")
 * Class UserLogin
 * @package App\Actions\User
 */
class UserLogin {
	public function __invoke( ViewResponders $viewResponders, AuthenticationUtils $authenticationUtils ): Response {

		$error        = $authenticationUtils->getLastAuthenticationError();
		$lastUserName = $authenticationUtils->getLastUsername();


		return $viewResponders( 'core/login.html.twig', [
			'lastUsername' => $lastUserName,
			'error'        => $error
		] );
	}
}