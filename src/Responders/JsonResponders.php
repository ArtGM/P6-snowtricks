<?php


namespace App\Responders;


use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class JsonResponders
 * @package App\Responders
 */
class JsonResponders {

	public function __invoke( ?array $datas = null, int $statusCode = Response::HTTP_OK, array $addHeaders = [] ): JsonResponse {
		return new JsonResponse( $datas, $statusCode, $addHeaders );
	}
}