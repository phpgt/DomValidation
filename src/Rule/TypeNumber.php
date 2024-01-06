<?php
namespace Gt\DomValidation\Rule;

use Gt\Dom\Element;

class TypeNumber extends Rule {
	/** @var string[] */
	protected array $attributes = [
		"type=number",
		"type=range",
	];

	public function isValid(Element $element, string|array $value, array $inputKvp):bool {
		if($value === "") {
			return true;
		}

		if(is_numeric($value)) {
			$value = (float)$value;

			if(false === $this->isValidMin(
				$element->getAttribute("min"),
				$value,
			)) {
				return false;
			}
			if(false === $this->isValidMax(
				$element->getAttribute("max"),
				$value,
			)) {
				return false;
			}
			if(false === $this->isValidStep(
				$element->getAttribute("min"),
				$element->getAttribute("step"),
				$value,
			)) {
				return false;
			}
		}
		else {
			return false;
		}

		return true;
	}

	public function getHint(Element $element, string $value):string {
		if(!is_numeric($value)) {
			return "Field must be a number";
		}

		$value = (float)$value;

		if($message = $this->getHintMinMax(
			$value,
			$element->getAttribute("min"),
			$element->getAttribute("max"),
		)) {
			return $message;
		}

		if($message = $this->getHintStep(
			$value,
			$element->getAttribute("min"),
			$element->getAttribute("step"),
		)) {
			return $message;
		}

		return "";
	}

	private function getHintMinMax(
		float $value,
		?string $min,
		?string $max,
	):?string {
		if(!is_null($min)) {
			if($value < $min) {
				return "Field value must not be lower than $min";
			}
		}
		if(!is_null($max)) {
			if($value > $max) {
				return "Field value must not be higher than $max";
			}
		}

		return null;
	}

	private function getHintStep(
		float $value,
		?string $min,
		?string $step,
	):?string {
		if(!is_null($min)) {
			$min = (float)$min;

			if(($value - $min) % $step !== 0) {
				return "Field value must be $min plus a multiple of $step";
			}
		}

		if($step && $value % $step !== 0) {
			return "Field value must be a multiple of $step";
		}

		return null;
	}

	private function isValidMin(?string $min, float $value):bool {
		if(is_null($min)) {
			return true;
		}
		$min = (float)$min;

		return $value >= $min;
	}

	private function isValidMax(?string $max, float $value):bool {
		if(is_null($max)) {
			return true;
		}
		$max = (float)$max;

		return $value <= $max;
	}

	private function isValidStep(?string $min, ?string $step, float $value):bool {
		if(!$step) {
			return true;
		}
		$step = (float)$step;

		if(is_null($min)) {
			return $value % $step === 0;
		}
		$min = (float)$min;

		return ($value - $min) % $step === 0;
	}
}
