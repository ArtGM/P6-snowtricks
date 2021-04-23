<?php


namespace App\Actions\Trick;

use App\Domain\Comment\Form\CommentFormType;
use App\Entity\Comment;
use App\Entity\Trick;
use App\Entity\User;
use App\Repository\CommentRepository;
use App\Repository\TricksRepository;
use App\Responders\RedirectResponders;
use App\Responders\ViewResponders;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * Class TrickSinglePage
 * @package App\Actions\Trick
 * @Route ("/trick/{slug}", name="trick-single")
 */
class TrickSinglePage {

	/**
	 * @var FormFactoryInterface
	 */
	private FormFactoryInterface $formFactory;
	/**
	 * @var EntityManagerInterface
	 */
	private EntityManagerInterface $entityManager;

	/**
	 * TrickSinglePage constructor.
	 *
	 * @param FormFactoryInterface $formFactory
	 * @param EntityManagerInterface $entityManager
	 */
	public function __construct( FormFactoryInterface $formFactory, EntityManagerInterface $entityManager ) {
		$this->formFactory   = $formFactory;
		$this->entityManager = $entityManager;
	}

	/**
	 * @param Request $request
	 * @param ViewResponders $viewResponders
	 * @param RedirectResponders $redirectResponders
	 * @param TricksRepository $tricksRepository
	 * @param CommentRepository $commentRepository
	 * @param string $slug
	 * @param TokenStorageInterface $tokenStorage
	 * @param FlashBagInterface $flashBag
	 * @param AuthorizationCheckerInterface $authorizationChecker
	 *
	 * @return Response
	 */
	public function __invoke(
		Request $request,
		ViewResponders $viewResponders,
		RedirectResponders $redirectResponders,
		TricksRepository $tricksRepository,
		CommentRepository $commentRepository,
		string $slug,
		TokenStorageInterface $tokenStorage,
		FlashBagInterface $flashBag,
		AuthorizationCheckerInterface $authorizationChecker
	): Response {

		/** @var Trick $singleTrick */
		$singleTrick      = $tricksRepository->findOneBy( [ 'slug' => $slug ] );
		$commentsList     = $commentRepository->findBy( [
			'trick' => $singleTrick
		], [ 'created_at' => 'DESC' ], 10 );
		$numberOfComments = count( $commentRepository->findBy( [
			'trick' => $singleTrick
		] ) );

		$token        = $tokenStorage->getToken();
		$medias       = $singleTrick->getMedias()->toArray();
		$templateVars = [
			'singleTrick'      => $singleTrick,
			'creationDate'     => $singleTrick->getCreatedAt()->format( 'd F Y' ),
			'trickGroup'       => $singleTrick->getTricksGroup()->getName(),
			'medias'           => $medias,
			'commentsList'     => $commentsList,
			'numberOfComments' => $numberOfComments,
			'currentPage'      => 1
		];

		if ( $authorizationChecker->isGranted( 'ROLE_USER' ) ) {
			$commentForm = $this->formFactory->create( CommentFormType::class )->handleRequest( $request );

			if ( $commentForm->isSubmitted() && $commentForm->isValid() ) {
				/** @var User $user */
				$user       = $token->getUser();
				$commentDto = $commentForm->getData();

				$newComment = Comment::createFromDto( $commentDto, $singleTrick, $user );
				$this->entityManager->persist( $newComment );
				$this->entityManager->flush();
				$flashBag->add( 'success', 'Your comment was successfully added!' );

				return $redirectResponders( 'trick-single', [ 'slug' => $slug ] );
			}

			$templateVars['commentForm'] = $commentForm->createView();
		}

		return $viewResponders( 'core/trick_single.html.twig', $templateVars );

	}
}
