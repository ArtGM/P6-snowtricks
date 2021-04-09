<?php


namespace App\Domain\Media\Form;


use App\Domain\Media\VideoDTO;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VideoFormType extends AbstractType {

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
				'title',
				TextType::class, [
					'required' => false,
					'label'    => false,
					'attr'     => [
						'hidden' => true
					]
				]
			)
			->add(
				'description',
				TextType::class, [
					'required' => false,
					'label'    => false,
					'attr'     => [
						'hidden' => true
					]
				]
			)
			->add(
				'url',
				UrlType::class, [
					'required' => false,
					'attr'     => [
						'class' => 'watchChange'
					]
				]
			);
	}

	/**
	 * @param OptionsResolver $resolver
	 */
	public function configureOptions( OptionsResolver $resolver ) {
		$resolver->setDefaults( [
			'data_class' => VideoDTO::class
		] );
	}
}
