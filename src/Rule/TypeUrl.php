<?php
namespace Gt\DomValidation\Rule;

use Gt\Dom\Element;

class TypeUrl extends Rule {
	protected array $attributes = [
		"type=url",
	];

	public function isValid(Element $element, string|array $value, array $inputKvp):bool {
		return $value === ""
		|| filter_var($value, FILTER_VALIDATE_URL) !== false;
	}

	public function getHint(Element $element, string $value):string {
		return "Field must be a URL";
	}
}
