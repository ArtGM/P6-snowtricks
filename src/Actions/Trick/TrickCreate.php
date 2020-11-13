<?php


namespace App\Actions\Trick;

use App\Domain\Trick\TrickDTO;
use App\Domain\Trick\TrickFormType;
use App\Entity\Trick;
use App\Responders\RedirectResponders;
use App\Responders\ViewResponders;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * @Route ("/trick-form", name="trick-form")
 * Class TrickCreate
 * @package App\Actions\Trick
 */
class TrickCreate {

	/** @var FormFactoryInterface  */
	private FormFactoryInterface $form_factory;

	/** @var EntityManagerInterface  */
	private EntityManagerInterface $entity_manager;

	/**
	 * TrickCreate constructor.
	 *
	 * @param EntityManagerInterface $entity_manager
	 * @param FormFactoryInterface $form_factory
	 */
	public function __construct(
		EntityManagerInterface $entity_manager,
		FormFactoryInterface $form_factory,
		UrlGeneratorInterface $url_generator
	) {
		$this->form_factory = $form_factory;
		$this->entity_manager = $entity_manager;
	}


	/**
	 * @param Request $request
	 * @param ViewResponders $view_responders
	 * @param RedirectResponders $redirect_responders
	 *
	 * @return RedirectResponse|Response
	 */
	public function __invoke(
		Request $request,
		ViewResponders $view_responders,
		RedirectResponders $redirect_responders
	) {
		$form = $this->form_factory->create( TrickFormType::class )
		                           ->handleRequest( $request );
		if ( $form->isSubmitted() && $form->isValid() ) {

			/** @var TrickDTO $trickDto */
			$trickDto = $form->getData();

			$trick = Trick::createFromDto($trickDto);
			$this->entity_manager->persist( $trick );
			$this->entity_manager->flush();

			return $redirect_responders('homepage');
		}

		return $view_responders('core/trick-form.html.twig', ['form' => $form->createView()]);
	}
}