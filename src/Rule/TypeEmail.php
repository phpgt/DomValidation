<?php
namespace GT\DomValidation\Rule;

use Gt\Dom\Element;
use GT\DomValidation\ValidityState\TypeMismatchException;

class TypeEmail extends Rule {
	protected array $attributes = [
		"type=email",
	];

	public function isValid(Element $element, string|array $value, array $inputKvp):bool {
		return $value === ""
		|| filter_var($value, FILTER_VALIDATE_EMAIL) !== false;
	}

	public function getExceptionClass(Element $element, string|array $value, array $inputKvp):string {
		return TypeMismatchException::class;
	}

	public function getHint(Element $element, string $value):string {
		return "Field must be an email address";
	}
}
