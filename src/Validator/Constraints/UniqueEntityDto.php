<?php


namespace App\Validator\Constraints;


use Symfony\Component\Validator\Constraint;

/**
 * Class UniqueEntityDto
 * @package App
 * @Annotation
 */
class UniqueEntityDto extends Constraint {
	public const NOT_UNIQUE_ERROR = 'e777db8d-3af0-41f6-8a73-55255375cdca';

	protected static $errorNames = [
		self::NOT_UNIQUE_ERROR => 'NOT_UNIQUE_ERROR',
	];

	public $em;

	public $entityClass;

	public $errorPath;

	public array $fieldMapping = [];

	public bool $ignoreNull = true;

	public string $message = 'This value is already used.';

	public string $repositoryMethod = 'findBy';

	public function getDefaultOption(): string {
		return 'entityClass';
	}

	public function getRequiredOptions(): array {
		return [ 'fieldMapping', 'entityClass' ];
	}

	public function getTargets() {
		return self::CLASS_CONSTRAINT;
	}

	public function validatedBy() {
		return UniqueEntityDtoValidator::class;
	}

}
