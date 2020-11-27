<?php


namespace App\Domain\Trick;



use App\Domain\Media\ImageFormType;
use App\Domain\TrickGroup\TrickGroupFormType;
use App\Entity\TrickGroup;
use App\Helpers\Number;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
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
					'entry_type' => ImageFormType::class,
					'allow_add'  => true,
					'prototype'  => true
				]
			)
			->add(
				'trickGroup',
				EntityType::class,
				[
					'class'        => TrickGroup::class,
					'choice_label' => function ( $trickGroup ) {
						return $trickGroup->getName();
					}
				]
			);

	}

	public function configureOptions( OptionsResolver $resolver ) {
		$resolver->setDefaults( [
			'data_class' => TrickDTO::class,
		] );
	}
}