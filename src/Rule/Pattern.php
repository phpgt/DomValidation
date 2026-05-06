<?php
namespace GT\DomValidation\Rule;

use Gt\Dom\Element;
use GT\DomValidation\ValidityState\PatternMismatchException;

class Pattern extends Rule {
	protected array $attributes = [
		"pattern",
	];

	public function isValid(Element $element, string|array $value, array $inputKvp):bool {
		$pattern = "/" . $element->getAttribute("pattern") . "/u";
		return (bool)preg_match($pattern, $value);
	}

	public function getExceptionClass(Element $element, string|array $value, array $inputKvp):string {
		return PatternMismatchException::class;
	}

	public function getHint(Element $element, string $value):string {
		$hint = "This field does not match the required pattern";

		if($title = $element->getAttribute("title")) {
			$hint .= ": $title";
		}

		return $hint;
	}
}
