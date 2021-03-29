<?php

namespace App\Service;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class AccessManager {

	/** @var TokenStorageInterface */
	private TokenStorageInterface $token;
	/** @var AuthorizationCheckerInterface */
	private AuthorizationCheckerInterface $authorizationChecker;

	public function __construct(
		TokenStorageInterface $tokenStorage,
		AuthorizationCheckerInterface $authorizationChecker
	) {
		$this->token                = $tokenStorage;
		$this->authorizationChecker = $authorizationChecker;
	}

	/**
	 * @return bool
	 */
	public function isGranted(): bool {
		$token           = $this->token->getToken();
		$isAuthenticated = $token->isAuthenticated();
		$isGranted       = $this->authorizationChecker->isGranted( 'ROLE_USER' );

		return $isAuthenticated && $isGranted;
	}

	/**
	 * @return TokenInterface|null
	 */
	public function getToken(): ?TokenInterface {
		return $this->token->getToken();
	}


}
