<?php
namespace Gt\DomValidation\Rule\Trait;

use Gt\Dom\Element;

/**
 * Elements that can have the `checked` attribute (radio buttons and checkboxes)
 * are considered "Checkable", and their validity is dependent on other elements
 * of the same name within the form.
 */
trait Checkable {
	/** @param string|array $value */
	private function checkedValueIsAvailable(Element $element, string|array $value):bool {
		$availableValues = [];
		$name = $element->name;

		/** @var Element $otherInput */
		foreach($element->form->getElementsByName($name) as $otherInput) {
			if($radioValue = $otherInput->value) {
				array_push($availableValues, $radioValue);
			}
		}

		$checkedValueList = [];
		if(is_array($value)) {
			$checkedValueList = $value;
		}
		else {
			$checkedValueList = [$value];
		}

		foreach($checkedValueList as $checkedValue) {
			if(!in_array($checkedValue, $availableValues)) {
				return false;
			}
		}

		return true;
	}
}
