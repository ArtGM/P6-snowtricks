<?php


namespace App\Domain\Media;


use Doctrine\DBAL\Types\StringType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ImageFormType extends AbstractType {

	/**
	 * @param FormBuilderInterface $builder
	 * @param array $options
	 */
	public function buildForm( FormBuilderInterface $builder, array $options ) {
		$builder
			->add(
				'id',
				TextType::class, [
					'required' => false,
					'label'    => false,
					'attr'     => [
						'hidden' => true
					]
				]
			)
			->add(
				'name',
				TextType::class
			)
			->add(
				'description',
				TextType::class,
			)
			->add(
				'file',
				FileType::class, [
					'required' => false
				]
			);
	}

	/**
	 * @param OptionsResolver $resolver
	 */
	public function configureOptions( OptionsResolver $resolver ) {
		$resolver->setDefaults( [
			'data_class' => ImageDTO::class
		] );
	}
}