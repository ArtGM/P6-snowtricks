<?php


namespace App\Actions\Trick;

use App\Domain\Comment\CommentFormType;
use App\Entity\Comment;
use App\Entity\Trick;
use App\Entity\User;
use App\Repository\CommentRepository;
use App\Repository\TricksRepository;
use App\Responders\RedirectResponders;
use App\Responders\ViewResponders;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

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

	public function __construct( FormFactoryInterface $formFactory ) {
		$this->formFactory = $formFactory;
	}

	public function __invoke(
		Request $request,
		ViewResponders $viewResponders,
		RedirectResponders $redirectResponders,
		TricksRepository $tricksRepository,
		CommentRepository $commentRepository,
		EntityManagerInterface $entityManager,
		string $slug,
		TokenStorageInterface $tokenStorage,
		FlashBagInterface $flashBag
	): Response {

		/** @var Trick $singleTrick */
		$singleTrick  = $tricksRepository->findOneBySlug( $slug );
		$commentsList = $commentRepository->findBy( [
			'trick' => $singleTrick
		] );


		$token           = $tokenStorage->getToken();
		$isAuthenticated = $token->isAuthenticated();

		$templateVars = [
			'singleTrick'  => $singleTrick,
			'commentsList' => $commentsList
		];

		if ( $isAuthenticated && ! empty( $token->getRoleNames() ) ) {
			$commentForm = $this->formFactory->create( CommentFormType::class )->handleRequest( $request );

			if ( $commentForm->isSubmitted() && $commentForm->isValid() ) {
				/** @var User $user */
				$user       = $token->getUser();
				$commentDto = $commentForm->getData();

				$newComment = Comment::createFromDto( $commentDto, $singleTrick, $user );
				$entityManager->persist( $newComment );
				$entityManager->flush();
				$flashBag->add( 'success', 'Your comment was successfully added!' );

				return $redirectResponders( 'trick-single', [ 'slug' => $slug ] );
			}

			$templateVars['commentForm'] = $commentForm->createView();
		}

		return $viewResponders( 'core/trick_single.html.twig', $templateVars );

	}
}