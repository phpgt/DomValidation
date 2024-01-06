<?php
namespace Gt\DomValidation\Rule;

use Gt\Dom\Element;

class MaxLength extends Rule {
	protected array $attributes = [
		"maxlength"
	];

	public function isValid(Element $element, string|array $value, array $inputKvp):bool {
		$maxLength = $element->getAttribute("maxlength");
		return strlen($value) <= $maxLength;
	}

	public function getHint(Element $element, string $value):string {
		$maxLength = $element->getAttribute("maxlength");
		return "This field's value must not contain more than $maxLength characters";
	}
}
