<?php


namespace App\Service;


use App\Entity\User;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;

class EncodePassword {

	/**
	 * @var EncoderFactoryInterface
	 */
	private EncoderFactoryInterface $encoder;

	public function __construct( EncoderFactoryInterface $encoder ) {
		$this->encoder = $encoder;
	}

	/**
	 * @param $plainPassword
	 *
	 * @return string
	 */
	public function encodedPassword( $plainPassword ): string {
		$passwordEncoder = $this->encoder->getEncoder( User::class );

		return $passwordEncoder->encodePassword( $plainPassword, null );
	}
}