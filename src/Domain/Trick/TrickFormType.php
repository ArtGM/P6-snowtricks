<?php


namespace App\Domain\Trick;



use App\Helpers\Number;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TrickFormType extends AbstractType {

	public function buildForm( FormBuilderInterface $builder, array $options ) {
		$builder
			->add(
				'name',
				TextType::class,
				[
					'attr' => [
						'label' => 'Name of trick',
					]
				]
			)
			->add(
				'description',
				TextType::class,
				[
					'attr' => [
						'label' => 'Description'
					]
				]
			);

	}

	public function configureOptions( OptionsResolver $resolver ) {
		$resolver->setDefaults( [
			'data_class' => TrickDTO::class,
		] );
	}
}