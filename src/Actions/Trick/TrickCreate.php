<?php


namespace App\Actions\Trick;

use App\Domain\Media\MediaDTO;
use App\Domain\Trick\TrickDTO;
use App\Domain\Trick\TrickFormType;
use App\Entity\Media;
use App\Entity\Trick;
use App\Responders\RedirectResponders;
use App\Responders\ViewResponders;
use App\Service\FileUploader;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

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
	 * @var SluggerInterface
	 */
	private SluggerInterface $slugger;

	private string $uploadDir;

	/**
	 * TrickCreate constructor.
	 *
	 * @param EntityManagerInterface $entity_manager
	 * @param FormFactoryInterface $form_factory
	 * @param SluggerInterface $slugger
	 * @param string $uploadDir
	 */
	public function __construct(
		EntityManagerInterface $entity_manager,
		FormFactoryInterface $form_factory,
		SluggerInterface $slugger,
		string $uploadDir
	) {
		$this->formFactory   = $form_factory;
		$this->entityManager = $entity_manager;
		$this->slugger       = $slugger;
		$this->uploadDir     = $uploadDir;
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
		$createTrickForm = $this->formFactory->create( TrickFormType::class )
		                                     ->handleRequest( $request );
		if ( $createTrickForm->isSubmitted() && $createTrickForm->isValid() ) {

			/** @var TrickDTO $trickDto */
			$trickDto = $createTrickForm->getData();

			$medias = $trickDto->images;

			$mediasEntity = [];

			if ( $medias ) {
				foreach ( $medias as $mediaDTO ) {
					$fileType          = $mediaDTO->file->getClientMimeType();
					$fileWithExtension = $fileUploader->upload( $mediaDTO->file );
					$mediasEntity[]    = $this->generateMediaEntity( $mediaDTO, $fileWithExtension, $fileType );
				}

			}


			$newTrick = Trick::createFromDto( $trickDto, $mediasEntity );
			$this->entityManager->persist( $newTrick );
			$this->entityManager->flush();

			return $redirectResponders( 'homepage' );
		}

		return $viewResponders( 'core/trick_form.html.twig', [ 'createTrickForm' => $createTrickForm->createView() ] );
	}


	private function generateMediaEntity( $mediaDTO, $fileWithExtension, $fileType ) {
		$newMedia = Media::createMedia( $mediaDTO, $fileWithExtension, $fileType );
		$this->entityManager->persist( $newMedia );
		$this->entityManager->flush();

		return $newMedia;
	}
}