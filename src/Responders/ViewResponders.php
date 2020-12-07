<?php


namespace App\Responders;


use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

class ViewResponders {
	/** @var Environment */
	protected Environment $templating;

	/**
	 * ViewResponder constructor.
	 *
	 * @param Environment $templating
	 */
	public function __construct(Environment $templating) {
		$this->templating = $templating;
	}

	public function __invoke(string $template, array $paramsTemplate = []): Response {
		return new Response($this->templating->render($template, $paramsTemplate));
	}
}