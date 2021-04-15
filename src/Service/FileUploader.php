<?php


namespace App\Service;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

class FileUploader {

	/** @var string */
	private string $uploadDir;
	/** @var SluggerInterface */
	private SluggerInterface $slugger;
	/**
	 * @var Filesystem
	 */
	private Filesystem $fileSystem;

	/**
	 * FileUploader constructor.
	 *
	 * @param string $uploadDir
	 * @param SluggerInterface $slugger
	 * @param Filesystem $fileSystem
	 */
	public function __construct( string $uploadDir, SluggerInterface $slugger, Filesystem $fileSystem ) {
		$this->uploadDir  = $uploadDir;
		$this->slugger    = $slugger;
		$this->fileSystem = $fileSystem;
	}

	/**
	 * @param UploadedFile $file
	 *
	 * @return string
	 */
	public function upload( UploadedFile $file ): string {
		$originalFilename = $file->getClientOriginalName();
		$safeFilename     = strtolower( $this->slugger->slug( $originalFilename ) );
		$fileName         = $safeFilename . '-' . uniqid() . '.' . $file->guessExtension();

		try {
			$file->move( $this->uploadDir, $fileName );
		} catch ( FileException $e ) {

		}

		return $fileName;
	}

	/**
	 * @param $fileName
	 */
	public function deleteFile( $fileName ) {
		try {
			$this->fileSystem->remove( $this->uploadDir . '/' . $fileName );
		} catch ( \Exception $e ) {

		}
	}
}
