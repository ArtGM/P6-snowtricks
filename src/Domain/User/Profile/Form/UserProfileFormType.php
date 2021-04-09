<?php


namespace App\Domain\User\Profile\Form;


use App\Domain\User\Profile\UserProfileDTO;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserProfileFormType extends AbstractType {
	public function buildForm( FormBuilderInterface $builder, array $options ) {

		$builder
			->add(
				'username',
				TextType::class
			)
			->add( 'email',
				EmailType::class
			)
			->add( 'avatar',
				TextType::class
			);
	}

	public function configureOptions( OptionsResolver $resolver ) {
		$resolver->setDefaults( [
			'data_class' => UserProfileDTO::class,
		] );
	}
}
