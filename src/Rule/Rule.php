<?php
namespace GT\DomValidation\Rule;

use GT\Dom\Element;
use GT\DomValidation\ValidityState\ValidityStateException;

/** @SuppressWarnings(PHPMD.NumberOfChildren) */
abstract class Rule {
	/**
	 * @var string[] Array of attribute strings that control this rule.
	 * For attributes that take a value, separate the key and value with an
	 * equals sign (e.g. "type=email"). For attributes without a value, pass the
	 * attribute name on its own (e.g. "required").
	 */
	protected array $attributes = [
		"name"
	];

	/** @return string[] */
	public function getAttributes():array {
		return $this->attributes;
	}

	/**
	 * @param string|array<string> $value Either a single string or multiple string values
	 * @param array<string, string|array<string>> $inputKvp
	 */
	abstract public function isValid(
		Element $element,
		string|array $value,
		array $inputKvp,
	):bool;

	/**
	 * @param string|array<string> $value Either a single string or multiple string values
	 * @param array<string, string|array<string>> $inputKvp
	 * @return class-string<\GT\DomValidation\ValidationException>
	 */
	public function getExceptionClass(
		Element $element,
		string|array $value,
		array $inputKvp,
	):string {
		return ValidityStateException::class;
	}

	abstract public function getHint(Element $element, string $value):string;
}
