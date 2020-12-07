<?php


namespace App\Responders;


use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class RedirectResponders {

	/** @var UrlGeneratorInterface */
	private UrlGeneratorInterface $url_generator;

	/**
	 * RedirectResponders constructor.
	 *
	 * @param UrlGeneratorInterface $url_generator
	 */
	public function __construct(UrlGeneratorInterface $url_generator) {
		$this->url_generator = $url_generator;
	}

	/**
	 * @param string $name
	 *
	 * @return RedirectResponse
	 */
	public function __invoke(string $name): RedirectResponse {
	return new RedirectResponse($this->url_generator->generate($name));
	}
}