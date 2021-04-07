<?php


namespace App\Service;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

class FileUploader {
	private string $uploadDir;
	private SluggerInterface $slugger;

	public function __construct( string $uploadDir, SluggerInterface $slugger ) {
		$this->uploadDir = $uploadDir;
		$this->slugger   = $slugger;
	}

	public function upload( UploadedFile $file ): string {
		$originalFilename = $file->getClientOriginalName();
		$safeFilename     = strtolower( $this->slugger->slug( $originalFilename ) );
		$fileName         = $safeFilename . '-' . uniqid() . '.' . $file->guessExtension();

		try {
			$file->move( $this->uploadDir, $fileName );
		} catch ( FileException $e ) {
			// ... handle exception if something happens during file upload
		}

		return $fileName;
	}
}
