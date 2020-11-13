<?php


namespace App\Domain\User;



use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
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
				PasswordType::class, [
				'attr' => [
					'label' => 'password'
				]
			] );
	}

	public function configureOptions( OptionsResolver $resolver ) {
		$resolver->setDefaults( [
			'data_class' => RegistrationDTO::class,
		] );
	}
}