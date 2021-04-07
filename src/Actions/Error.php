<?php


namespace App\Actions;


use App\Responders\ViewResponders;
use Symfony\Component\ErrorHandler\Exception\FlattenException;
use Symfony\Component\HttpFoundation\Response;


class Error {

	public static function show( ViewResponders $viewResponders, FlattenException $exception ): Response {
		$template = 'error/error' . $exception->getStatusCode() . '.html.twig';

		return $viewResponders( $template );
	}

}
