<?php
namespace Gt\DomValidation\Rule;

use Gt\Dom\Element;

class Required extends Rule {
	protected array $attributes = [
		"required",
	];

	public function isValid(Element $element, string|array $value, array $inputKvp):bool {
		return !empty($value);
	}

	public function getHint(Element $element, string $value):string {
		return "This field is required";
	}
}
