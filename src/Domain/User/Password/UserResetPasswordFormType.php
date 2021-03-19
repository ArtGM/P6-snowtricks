<?php


namespace App\Domain\User\Password;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserResetPasswordFormType extends AbstractType {

	public function buildForm( FormBuilderInterface $builder, array $options ) {

		$builder
			->add(
				'password',
				RepeatedType::class, [
				'type'            => PasswordType::class,
				'invalid_message' => 'The password fields must match.',
				'required'        => true,
				'first_options'   => [ 'label' => 'New Password' ],
				'second_options'  => [ 'label' => 'Confirm New Password' ],
			] );

	}

	public function configureOptions( OptionsResolver $resolver ) {
		$resolver->setDefaults( [
			'data_class' => UserResetPasswordDTO::class,
		] );
	}
}