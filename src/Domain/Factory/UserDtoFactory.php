<?php


namespace App\Domain\Factory;


use App\Domain\User\Profile\UserProfileDTO;
use App\Entity\User;
use App\Repository\MediaRepository;

class UserDtoFactory {

	/** @var MediaDtoFactory */
	private MediaDtoFactory $mediaToDto;
	/**
	 * @var MediaRepository
	 */
	private MediaRepository $mediaRepository;


	/**
	 * TrickDTOFactory constructor.
	 *
	 * @param MediaDtoFactory $mediaToDto
	 * @param MediaRepository $mediaRepository
	 */
	public function __construct( MediaDtoFactory $mediaToDto, MediaRepository $mediaRepository) {
		$this->mediaToDto = $mediaToDto;
		$this->mediaRepository = $mediaRepository;

	}

	/**
	 * @param User $user
	 *
	 * @return UserProfileDTO
	 */
	public function create( User $user ): UserProfileDTO {
		$userProfileDto = new UserProfileDTO();
		$avatarId = $user->getAvatar();
		if (isset($avatarId)) {
			$mediaEntity = $this->mediaRepository->find($avatarId);
			$imageDto = $this->mediaToDto->createImage($mediaEntity);
			$userProfileDto->avatar       = $imageDto;
		}

		$userProfileDto->username        = $user->getUsername();
		$userProfileDto->email        = $user->getEmail();

		return $userProfileDto;
	}
}