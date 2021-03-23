<?php


namespace App\Actions\Trick;


use App\Entity\Trick;
use App\Repository\TricksRepository;
use App\Responders\RedirectResponders;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class TrickDelete
 * @package App\Actions\Trick
 * @Route ("/delete-trick/{slug}", name="delete-trick")
 */
class TrickDelete {

	/**
	 * @var EntityManagerInterface
	 */
	private EntityManagerInterface $entityManager;
	/**
	 * @var FlashBagInterface
	 */
	private FlashBagInterface $flashBag;

	public function __construct( EntityManagerInterface $entityManager, FlashBagInterface $flashBag ) {
		$this->entityManager = $entityManager;
		$this->flashBag      = $flashBag;
	}


	public function __invoke( TricksRepository $tricksRepository, RedirectResponders $redirectResponders, string $slug ): RedirectResponse {
		/** @var Trick $trickToDelete */
		$trickToDelete = $tricksRepository->findOneBy( [ 'slug' => $slug ] );
		$medias        = $trickToDelete->getMedias();

		foreach ( $medias as $media ) {
			$trickToDelete->removeMedia( $media );
		}

		$this->entityManager->remove( $trickToDelete );
		$this->entityManager->flush();
		$this->flashBag->add( 'success', 'Trick Deleted !' );

		return $redirectResponders( 'homepage' );

	}

}