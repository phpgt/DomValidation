<?php
namespace GT\DomValidation\Rule;

use GT\Dom\Element;
use GT\Dom\ElementType;
use GT\DomValidation\Rule\Trait\Checkable;

class TypeRadio extends Rule {
	use Checkable;

	public function isValid(Element $element, string|array $value, array $inputKvp):bool {
		if($element->elementType !== ElementType::HTMLInputElement) {
			return true;
		}
		if($element->type !== "radio") {
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
