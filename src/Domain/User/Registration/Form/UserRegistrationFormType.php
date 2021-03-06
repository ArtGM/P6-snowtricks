<?php


namespace App\Domain\User\Registration\Form;


use App\Domain\User\Registration\UserRegistrationDTO;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserRegistrationFormType extends AbstractType {

	public function buildForm( FormBuilderInterface $builder, array $options ) {

		$builder
			->add(
				'name',
				TextType::class
			)
			->add( 'email',
				EmailType::class
			)
			->add(
				'password',
				RepeatedType::class, [
				'type'            => PasswordType::class,
				'invalid_message' => 'The password fields must match.',
				'required'        => true,
				'first_options'   => [ 'label' => 'Password' ],
				'second_options'  => [ 'label' => 'Confirm Password' ],
			] );
	}

	public function configureOptions( OptionsResolver $resolver ) {
		$resolver->setDefaults( [
			'data_class' => UserRegistrationDTO::class,
		] );
	}
}
