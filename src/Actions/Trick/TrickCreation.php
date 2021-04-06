<?php


namespace App\Actions\Trick;

use App\Domain\Media\Handlers\MediaHandler;
use App\Domain\Trick\Form\TrickDTO;
use App\Domain\Trick\Form\TrickFormType;
use App\Entity\Trick;
use App\Responders\RedirectResponders;
use App\Responders\ViewResponders;
use App\Service\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Csrf\TokenStorage\TokenStorageInterface;
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
	 * @param FlashBagInterface $flashBag
	 * @param TokenStorageInterface $tokenStorage
	 * @param AuthorizationCheckerInterface $authorizationChecker
	 *
	 * @return RedirectResponse|Response
	 */
	public function __invoke(
		Request $request,
		ViewResponders $viewResponders,
		RedirectResponders $redirectResponders,
		FileUploader $fileUploader,
		FlashBagInterface $flashBag,
		TokenStorageInterface $tokenStorage,
		AuthorizationCheckerInterface $authorizationChecker

	) {

		if ( ! $authorizationChecker->isGranted( 'ROLE_USER' ) ) {
			$flashBag->add( 'warning', 'please sign before add a new Trick' );

			return $redirectResponders( 'user_login' );
		}


		$trickForm = $this->getForm( $request );

		if ( $trickForm->isSubmitted() && $trickForm->isValid() ) {

			$newTrick = $this->createNewTrickFromFormData( $trickForm );

			$flashBag->add( 'success', $newTrick->getName() . 'Trick Successfully Created !' );

			return $redirectResponders( 'homepage' );
		}

		return $viewResponders( 'core/trick_create.html.twig', [ 'trickForm' => $trickForm->createView() ] );
	}

	/**
	 * @param Request $request
	 *
	 * @return FormInterface
	 */
	private function getForm( Request $request ): FormInterface {
		return $this->formFactory->create( TrickFormType::class, null, [ 'validation_groups' => [ 'create' ] ] )
		                         ->handleRequest( $request );
	}

	/**
	 * @param TrickDTO $trickDto
	 *
	 * @return array
	 */
	private function getMediasEntities( TrickDTO $trickDto ): array {
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

		return $mediasEntity;
	}

	/**
	 * @param FormInterface $trickForm
	 *
	 * @return Trick
	 */
	private function createNewTrickFromFormData( FormInterface $trickForm ): Trick {
		/** @var TrickDTO $trickDto */
		$trickDto = $trickForm->getData();

		$mediasEntity = $this->getMediasEntities( $trickDto );

		$newTrick = Trick::createFromDto( $trickDto, $mediasEntity );

		$this->entityManager->persist( $newTrick );
		$this->entityManager->flush();

		return $newTrick;
	}


}