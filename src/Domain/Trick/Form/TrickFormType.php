<?php


namespace App\Domain\Trick\Form;


use App\Domain\Media\Form\ImageFormType;
use App\Domain\Media\Form\VideoFormType;
use App\Domain\Trick\TrickDTO;
use App\Entity\TrickGroup;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
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
				TextareaType::class,
				[
					'attr' => [
						'label' => 'Description'
					]
				]
			)
			->add(
				'images',
				CollectionType::class, [
					'entry_type'   => ImageFormType::class,
					'allow_add'    => true,
					'by_reference' => false,
					'required'     => false
				]
			)
			->add(
				'video',
				CollectionType::class, [
					'entry_type'   => VideoFormType::class,
					'allow_add'    => true,
					'by_reference' => false,
					'required'     => false
				]
			)
			->add(
				'trickGroup',
				EntityType::class,
				[
					'class'        => TrickGroup::class,
					'choice_label' => function ( TrickGroup $trickGroup ) {
						return $trickGroup->getName();
					}
				]
			);


	}

	public function configureOptions( OptionsResolver $resolver ) {
		$resolver->setDefaults( [
			'data_class' => TrickDTO::class
		] );
	}
}
