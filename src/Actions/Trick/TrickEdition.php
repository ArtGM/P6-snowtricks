<?php


namespace App\Actions\Trick;

use App\Actions\Media\MediaCreation;
use App\Domain\Factory\TrickDtoFactory;
use App\Domain\Trick\TrickDTO;
use App\Domain\Trick\TrickFormType;
use App\Entity\Trick;
use App\Repository\TricksRepository;
use App\Responders\RedirectResponders;
use App\Responders\ViewResponders;
use App\Service\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


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

	public function __invoke(
		Request $request,
		TricksRepository $tricksRepository,
		ViewResponders $viewResponders,
		EntityManagerInterface $entityManager,
		string $slug,
		FileUploader $fileUploader,
		RedirectResponders $redirectResponders,
		MediaCreation $mediaCreation
	): Response {

		$trick = $tricksRepository->findOneBySlug( $slug );

		$trickDto = $this->trickDtoFactory->create( $trick );

		$trickEditionForm = $this->formFactory->create( TrickFormType::class, $trickDto )->handleRequest( $request );

		if ( $trickEditionForm->isSubmitted() && $trickEditionForm->isValid() ) {
			dump( 'toto' );
			exit();
			/** @var TrickDTO $trickDto */
			$trickDto = $trickEditionForm->getData();

			$medias = $trickDto->images;

			if ( $medias ) {
				foreach ( $medias as $mediaDTO ) {
					$fileType          = $mediaDTO->file->getClientMimeType();
					$fileWithExtension = $fileUploader->upload( $mediaDTO->file );
					$mediasEntity[]    = $mediaCreation->generateMediaEntity( $mediaDTO, $fileWithExtension, $fileType );
				}

			}

			$this->entityManager->persist( $trick );
			$this->entityManager->flush();

			return $redirectResponders( 'homepage' );
		}

		$templateVars = [
			'editTrickForm' => $trickEditionForm->createView()
		];

		return $viewResponders( 'core/trick_edit.html.twig', $templateVars );
	}
}