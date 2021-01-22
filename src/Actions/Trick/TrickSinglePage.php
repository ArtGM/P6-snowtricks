<?php


namespace App\Actions\Trick;

use App\Entity\Trick;
use App\Responders\ViewResponders;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class TrickSinglePage
 * @package App\Actions\Trick
 * @Route ("/trick/{slug}", name="trick-single")
 */
class TrickSinglePage {

	public function __invoke( Request $request, ViewResponders $viewResponders, EntityManagerInterface $entityManager, string $slug ): Response {
		$trickRepository = $entityManager->getRepository( Trick::class );
		$singleTrick     = $trickRepository->findOneByName( $slug );

		//dump($singleTrick->get_medias());
		return $viewResponders( 'core/trick_single.html.twig', [
			'singleTrick' => $singleTrick,
		] );
	}
}