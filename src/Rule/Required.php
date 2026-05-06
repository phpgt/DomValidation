<?php
namespace GT\DomValidation\Rule;

use Gt\Dom\Element;
use Gt\Dom\ElementType;
use GT\DomValidation\ValidityState\ValueMissingException;

class Required extends Rule {
	protected array $attributes = [
		"required",
	];

	public function isValid(Element $element, string|array $value, array $inputKvp):bool {
		return !empty($value);
	}

	public function getExceptionClass(Element $element, string|array $value, array $inputKvp):string {
		return ValueMissingException::class;
	}

	public function getHint(Element $element, string $value):string {
		if($element->elementType === ElementType::HTMLSelectElement) {
			return "Please select an item in the list";
		}

		if($element->elementType === ElementType::HTMLInputElement
		&& $element->type === "radio") {
			return "Please select one of these options";
		}

		return "This field is required";
	}
}
