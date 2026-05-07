<?php
namespace GT\DomValidation\Rule;

use GT\Dom\Element;
use GT\Dom\ElementType;

class SelectElement extends Rule {
	public function isValid(Element $element, string|array $value, array $inputKvp):bool {
		$availableValues = [];

		if($element->elementType !== ElementType::HTMLSelectElement) {
			return true;
		}

		if($value === "") {
			return true;
		}

		foreach($element->options as $option) {
			if($optionValue = $option->value) {
				array_push($availableValues, $optionValue);
			}
		}

		if(!in_array($value, $availableValues)) {
			return false;
		}

		return true;
	}

	public function getHint(Element $element, string $value):string {
		return "This field's value must match one of the available options";
	}
}
