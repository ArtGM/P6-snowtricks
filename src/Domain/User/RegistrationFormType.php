<?php


namespace App\Domain\User;



use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegistrationFormType extends AbstractType {

	public function buildForm( FormBuilderInterface $builder, array $options ) {

		$builder
			->add(
				'name',
				TextType::class,
				[
					'attr' => [
						'label' => 'username'
					]
				] )
			->add( 'email',
				EmailType::class,
				[
					'attr' => [
						'label' => 'username'
					]
				] )
			->add(
				'password',
				RepeatedType::class, [
				'type'            => PasswordType::class,
				'invalid_message' => 'The password fields must match.',
				'options'         => [ 'attr' => [ 'class' => 'password-field' ] ],
				'required'        => true,
				'first_options'   => [ 'label' => 'Password' ],
				'second_options'  => [ 'label' => 'Repeat Password' ],
			] );
	}

	public function configureOptions( OptionsResolver $resolver ) {
		$resolver->setDefaults( [
			'data_class' => RegistrationDTO::class,
		] );
	}
}