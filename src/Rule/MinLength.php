<?php
namespace Gt\DomValidation\Rule;

use Gt\Dom\Element;

class MinLength extends Rule {
	protected array $attributes = [
		"minlength"
	];

	public function isValid(Element $element, string|array $value, array $inputKvp):bool {
		$minLength = $element->getAttribute("minlength");
		return strlen($value) >= $minLength;
	}

	public function getHint(Element $element, string $value):string {
		$minLength = $element->getAttribute("minlength");
		return "This field's value must contain at least $minLength characters";
	}
}
