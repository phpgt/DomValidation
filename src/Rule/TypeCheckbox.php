<?php
namespace Gt\DomValidation\Rule;

use Gt\Dom\Element;
use Gt\Dom\ElementType;
use Gt\DomValidation\Rule\Trait\Checkable;

class TypeCheckbox extends Rule {
	use Checkable;

	public function isValid(Element $element, string|array $value, array $inputKvp):bool {
		if($element->elementType !== ElementType::HTMLInputElement) {
			return true;
		}
		if($element->type !== "checkbox") {
			return true;
		}

		if($value === "") {
			return true;
		}

		if(!$element->form) {
			return true;
		}

		if(!$this->checkedValueIsAvailable($element, $value)) {
			return false;
		}

		return true;
	}

	public function getHint(Element $element, string $value):string {
		return "This field's value must match one of the available options";
	}
}
