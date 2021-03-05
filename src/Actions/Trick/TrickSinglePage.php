<?php


namespace App\Actions\Trick;

use App\Domain\Comment\CommentFormType;
use App\Entity\Trick;
use App\Responders\ViewResponders;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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
		EntityManagerInterface $entityManager,
		string $slug,
		TokenStorageInterface $tokenStorage
	): Response {
		$trickRepository = $entityManager->getRepository( Trick::class );
		$singleTrick     = $trickRepository->findOneBySlug( $slug );

		$token           = $tokenStorage->getToken();
		$isAuthenticated = $token->isAuthenticated();
		if ( $isAuthenticated && ! empty( $token->getRoleNames() ) ) {
			$createCommentForm = $this->formFactory->create( CommentFormType::class )->handleRequest( $request );

			return $viewResponders( 'core/trick_single.html.twig', [
				'singleTrick' => $singleTrick,
				'commentForm' => $createCommentForm->createView()
			] );
		}

		return $viewResponders( 'core/trick_single.html.twig', [
			'singleTrick' => $singleTrick,
		] );

	}
}