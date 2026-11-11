<?php
namespace GT\DomValidation\Rule;

use GT\Dom\Element;
use GT\DomValidation\ValidityState\TooLongException;

class MaxLength extends Rule {
	protected array $attributes = [
		"maxlength"
	];

	public function isValid(Element $element, string|array $value, array $inputKvp):bool {
		if(!is_string($value) || !mb_check_encoding($value, "UTF-8")) {
			return false;
		}

		if($value === "") {
			return true;
		}

		// HTML measures text length in UTF-16 code units, not UTF-8 bytes.
		$length = strlen(mb_convert_encoding($value, "UTF-16LE", "UTF-8")) / 2;
		$maxLength = $element->getAttribute("maxlength");
		return $length <= $maxLength;
	}

	public function getExceptionClass(Element $element, string|array $value, array $inputKvp):string {
		return TooLongException::class;
	}

	public function getHint(Element $element, string $value):string {
		$maxLength = $element->getAttribute("maxlength");
		return "This field's value must not contain more than $maxLength characters";
	}
}
