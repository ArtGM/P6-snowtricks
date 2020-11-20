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

	/** @var FormFactoryInterface */
	private FormFactoryInterface $formFactory;

	/** @var EntityManagerInterface */
	private EntityManagerInterface $entityManager;

	/**
	 * TrickCreate constructor.
	 *
	 * @param EntityManagerInterface $entity_manager
	 * @param FormFactoryInterface $form_factory
	 */
	public function __construct(
		EntityManagerInterface $entity_manager,
		FormFactoryInterface $form_factory
	) {
		$this->formFactory   = $form_factory;
		$this->entityManager = $entity_manager;
	}


	/**
	 * @param Request $request
	 * @param ViewResponders $viewResponders
	 * @param RedirectResponders $redirectResponders
	 *
	 * @return RedirectResponse|Response
	 */
	public function __invoke(
		Request $request,
		ViewResponders $viewResponders,
		RedirectResponders $redirectResponders
	) {
		$createTrickForm = $this->formFactory->create( TrickFormType::class )
		                                     ->handleRequest( $request );
		if ( $createTrickForm->isSubmitted() && $createTrickForm->isValid() ) {

			/** @var TrickDTO $trickDto */
			$trickDto = $createTrickForm->getData();

			$newTrick = Trick::createFromDto( $trickDto );
			$this->entityManager->persist( $newTrick );
			$this->entityManager->flush();

			return $redirectResponders( 'homepage' );
		}

		return $viewResponders( 'core/trick_form.html.twig', [ 'createTrickForm' => $createTrickForm->createView() ] );
	}
}