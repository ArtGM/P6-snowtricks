<?php


namespace App\Actions\Trick;

use App\Domain\Factory\TrickDtoFactory;
use App\Domain\Trick\Handlers\TrickEditionHandler;
use App\Domain\Trick\Form\TrickFormType;
use App\Repository\TricksRepository;
use App\Responders\RedirectResponders;
use App\Responders\ViewResponders;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;


/**
 * Class TrickEdition
 * @package App\Actions\Trick
 * @Route("/trick/{slug}/edit", name="trick-edition")
 */
class TrickEdition {

	/** @var FormFactoryInterface */
	private FormFactoryInterface $formFactory;

	/** @var EntityManagerInterface */
	private EntityManagerInterface $entityManager;

	/** @var TrickDtoFactory */
	private TrickDtoFactory $trickDtoFactory;

	public function __construct(
		FormFactoryInterface $formFactory,
		EntityManagerInterface $entityManager,
		TrickDtoFactory $trickDtoFactory
	) {

		$this->formFactory     = $formFactory;
		$this->entityManager   = $entityManager;
		$this->trickDtoFactory = $trickDtoFactory;
	}

	/**
	 * @param Request $request
	 * @param TricksRepository $tricksRepository
	 * @param ViewResponders $viewResponders
	 * @param TrickEditionHandler $trickEditionHandler
	 * @param string $slug
	 * @param RedirectResponders $redirectResponders
	 * @param FlashBagInterface $flashBag
	 * @param AuthorizationChecker $authorizationChecker
	 *
	 * @return Response
	 */
	public function __invoke(
		Request $request,
		TricksRepository $tricksRepository,
		ViewResponders $viewResponders,
		TrickEditionHandler $trickEditionHandler,
		string $slug,
		RedirectResponders $redirectResponders,
		FlashBagInterface $flashBag,
		AuthorizationCheckerInterface $authorizationChecker
	): Response {

		if ( ! $authorizationChecker->isGranted( 'ROLE_USER' ) ) {
			$flashBag->add( 'warning', 'you can\'t edit tricks' );

			return $redirectResponders( 'homepage' );
		}
		$trick = $tricksRepository->findOneBySlug( $slug );

		$trickDto = $this->trickDtoFactory->create( $trick );

		$trickEditionForm = $this->formFactory
			->create( TrickFormType::class, $trickDto, [ 'validation_groups' => [ 'update' ] ] )
			->handleRequest( $request );

		if ( $trickEditionForm->isSubmitted() && $trickEditionForm->isValid() ) {
			$trickEditionHandler->handle( $trickEditionForm, $trick );
			$flashBag->add( 'success', 'Trick was updated !' );

			return $redirectResponders( 'homepage' );
		}

		$templateVars = [
			'editTrickForm' => $trickEditionForm->createView(),
			'trickMedias'   => $trick->getMedias(),
			'trickName'     => $trick->getName()
		];

		return $viewResponders( 'core/trick_edit.html.twig', $templateVars );
	}
}
