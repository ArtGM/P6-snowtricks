<?php


namespace App\Domain\Comment;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class CommentFormType extends AbstractType {

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
				'content',
				TextType::class, [
					'required' => true,
				]
			)
			->add(
				'trick',
				TextType::class, [
					'required' => true,
					'label'    => false,
					'attr'     => [
						'hidden' => true
					]
				]
			)
			->add(
				'user',
				TextType::class, [
					'required' => true,
					'label'    => false,
					'attr'     => [
						'hidden' => true
					]
				]
			);
	}

	/**
	 * @param OptionsResolver $resolver
	 */
	public function configureOptions( OptionsResolver $resolver ) {
		$resolver->setDefaults( [
			'data_class' => CommentDTO::class
		] );
	}

}