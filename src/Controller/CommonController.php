<?php


namespace App\Controller;


use App\Domain\Trick\TrickDTO;
use App\Domain\Trick\TrickFormType;
use App\Entity\Trick;
use App\Repository\TricksRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
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
	/**
	 * @var FormFactoryInterface
	 */
	private $form_factory;
	/**
	 * @var EntityManagerInterface
	 */
	private $entity_manager;
	/**
	 * @var UrlGeneratorInterface
	 */
	private $url_generator;

	public function __construct(
		Environment $templating,
		TricksRepository $trick,
		EntityManagerInterface $entity_manager,
		FormFactoryInterface $form_factory,
		UrlGeneratorInterface $url_generator
	) {
		$this->templating     = $templating;
		$this->trick          = $trick;
		$this->form_factory   = $form_factory;
		$this->entity_manager = $entity_manager;
		$this->url_generator  = $url_generator;
	}

	/**
	 * @Route("/", name="homepage")
	 * @return Response
	 * @throws Exception
	 */
	public function index(): Response {
		return new Response(
			$this->templating->render( 'core/index.html.twig' )
		);
	}

	/**
	 * @Route("/trick-form", name="add-trick")
	 * @param Request $request
	 *
	 * @return Response
	 */
	public function trick_form( Request $request ): Response {

		$form = $this->form_factory->create( TrickFormType::class )
		                           ->handleRequest( $request );
		if ( $form->isSubmitted() && $form->isValid() ) {
			/** @var TrickDTO $trickDto */
			$trickDto = $form->getData();
			$trick = Trick::createFromDto($trickDto);
			$this->entity_manager->persist( $trick );
			$this->entity_manager->flush();

			return new RedirectResponse($this->url_generator->generate('homepage'));
		}

		return new Response(
			$this->templating->render( 'core/trick-form.html.twig', [
				'form' => $form->createView()
			] )
		);
	}
}