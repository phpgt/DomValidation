<?php
namespace GT\DomValidation\Rule;

use GT\Dom\Element;
use GT\DomValidation\ValidityState\TooShortException;

class MinLength extends Rule {
	protected array $attributes = [
		"minlength"
	];

	public function isValid(Element $element, string|array $value, array $inputKvp):bool {
		$minLength = $element->getAttribute("minlength");
		return strlen($value) >= $minLength;
	}

	public function getExceptionClass(Element $element, string|array $value, array $inputKvp):string {
		return TooShortException::class;
	}

	public function getHint(Element $element, string $value):string {
		$minLength = $element->getAttribute("minlength");
		return "This field's value must contain at least $minLength characters";
	}
}
