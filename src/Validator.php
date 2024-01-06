<?php
namespace Gt\DomValidation;

use Gt\Dom\Element;
use Gt\DomValidation\Rule\Rule;
use Stringable;
use Traversable;

class Validator {
	protected ?ValidationRules $rules;
	protected ErrorList $errorList;

	public function __construct(?ValidationRules $rules = null) {
		if(is_null($rules)) {
			$rules = new DefaultValidationRules();
		}

		$this->rules = $rules;
	}

	/** @param iterable<string, string|array<string>> $inputKvp Associative array of user input */
	public function validate(Element $form, iterable|object $inputKvp):void {
		$this->errorList = new ErrorList();

		if(is_object($inputKvp)) {
			$inputKvp = $this->convertObjectToKvp($inputKvp);
		}

		foreach($this->rules?->getAttributeRuleList() ?? [] as $attrString => $ruleArray) {
			$this->buildErrorList(
				$form,
				$attrString,
				$ruleArray,
				$inputKvp
			);
		}

		$errorCount = count($this->errorList);
		if($errorCount > 0) {
			$collectiveNoun = $errorCount === 1 ? "is" : "are";
			$fieldWord = $errorCount === 1 ? "field" : "fields";
			throw new ValidationException(
				"There $collectiveNoun $errorCount invalid $fieldWord"
			);
		}
	}

	public function getLastErrorList():ErrorList {
		return $this->errorList;
	}

	/**
	 * @param array<Rule> $ruleArray
	 * @param array<string, string|array<string>> $inputKvp
	 */
	protected function buildErrorList(
		Element $form,
		int|string $attrString,
		array $ruleArray,
		array $inputKvp,
	): void {
		/** @var Element $element */
		foreach ($form->querySelectorAll("[$attrString]") as $element) {
			$name = $element->getAttribute("name");
			$name = strtok($name, "[]");

			foreach ($ruleArray as $rule) {
				if (!$rule->isValid($element, $inputKvp[$name] ?? "", $inputKvp)) {
					$this->errorList->add(
						$element,
						$rule->getHint($element, $this->normalise($inputKvp[$name]))
					);
				}
			}
		}
	}

	/** @return array<string, string> */
	private function convertObjectToKvp(object $obj):array {
		if(method_exists($obj, "asArray")) {
			return $obj->asArray();
		}

		if($obj instanceof Traversable) {
			return iterator_to_array($obj);
		}

		$array = [];
		foreach(get_object_vars($obj) as $key => $value) {
			if(is_scalar($value) || $value instanceof Stringable) {
				$value = (string)$value;
			}
			else {
				$value = "";
			}

			$array[$key] = $value;
		}
		return $array;
	}

	private function normalise(array|string|null $input):string {
		if(is_string($input)) {
			return $input;
		}

		return implode(", ", $input ?? []);
	}
}
