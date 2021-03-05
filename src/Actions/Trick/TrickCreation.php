<?php


namespace App\Actions\Trick;

use App\Domain\Media\Handlers\MediaHandler;
use App\Domain\Trick\TrickDTO;
use App\Domain\Trick\TrickFormType;
use App\Entity\Trick;
use App\Responders\RedirectResponders;
use App\Responders\ViewResponders;
use App\Service\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

/**
 * @Route ("/trick-form", name="trick-form")
 * Class TrickCreation
 * @package App\Actions\Trick
 */
class TrickCreation {

	/** @var FormFactoryInterface */
	private FormFactoryInterface $formFactory;

	/** @var EntityManagerInterface */
	private EntityManagerInterface $entityManager;

	/**
	 * @var SluggerInterface
	 */
	private SluggerInterface $slugger;

	/**
	 * @var string
	 */
	private string $uploadDir;

	/**
	 * @var MediaHandler
	 */
	private MediaHandler $mediaCreation;

	/**
	 * TrickCreation constructor.
	 *
	 * @param EntityManagerInterface $entityManager
	 * @param FormFactoryInterface $formFactory
	 * @param SluggerInterface $slugger
	 * @param string $uploadDir
	 * @param MediaHandler $mediaCreation
	 */
	public function __construct(
		EntityManagerInterface $entityManager,
		FormFactoryInterface $formFactory,
		SluggerInterface $slugger,
		string $uploadDir,
		MediaHandler $mediaCreation
	) {
		$this->formFactory   = $formFactory;
		$this->entityManager = $entityManager;
		$this->slugger       = $slugger;
		$this->uploadDir     = $uploadDir;
		$this->mediaCreation = $mediaCreation;
	}


	/**
	 * @param Request $request
	 * @param ViewResponders $viewResponders
	 * @param RedirectResponders $redirectResponders
	 *
	 * @param FileUploader $fileUploader
	 *
	 * @return RedirectResponse|Response
	 */
	public function __invoke(
		Request $request,
		ViewResponders $viewResponders,
		RedirectResponders $redirectResponders,
		FileUploader $fileUploader
	) {
		$trickForm = $this->formFactory->create( TrickFormType::class, null, [ 'validation_groups' => [ 'create' ] ] )
		                               ->handleRequest( $request );

		if ( $trickForm->isSubmitted() && $trickForm->isValid() ) {

			/** @var TrickDTO $trickDto */
			$trickDto = $trickForm->getData();

			$images = $trickDto->images;
			$video  = $trickDto->video;

			$mediasEntity = [];

			if ( $images ) {
				foreach ( $images as $imageDto ) {
					$mediasEntity[] = $this->mediaCreation->generateImage( $imageDto );
				}
			}

			if ( $video ) {
				foreach ( $video as $videoDto ) {
					$mediasEntity[] = $this->mediaCreation->generateVideo( $videoDto );
				}
			}

			$newTrick = Trick::createFromDto( $trickDto, $mediasEntity );

			$this->entityManager->persist( $newTrick );
			$this->entityManager->flush();

			return $redirectResponders( 'homepage' );
		}

		return $viewResponders( 'core/trick_create.html.twig', [ 'trickForm' => $trickForm->createView() ] );
	}


}