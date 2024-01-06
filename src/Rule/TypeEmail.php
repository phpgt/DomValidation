<?php
namespace Gt\DomValidation\Rule;

use Gt\Dom\Element;

class TypeEmail extends Rule {
	protected array $attributes = [
		"type=email",
	];

	public function isValid(Element $element, string|array $value, array $inputKvp):bool {
		return $value === ""
		|| filter_var($value, FILTER_VALIDATE_EMAIL) !== false;
	}

	public function getHint(Element $element, string $value):string {
		return "Field must be an email address";
	}
}
