<?php
namespace Gt\DomValidation;

use Countable;
use Gt\Dom\Element;
use Iterator;
use Gt\DomValidation\ValidityState\ValidityStateException;

/** @implements Iterator<string, string> */
class ErrorList implements Countable, Iterator {
	/** @var array<string, string[]> */
	protected array $errorArray;
	/** @var array<string, array<class-string<ValidationException>>> */
	protected array $exceptionClassArray;
	protected int $iteratorKey;

	public function __construct() {
		$this->errorArray = [];
		$this->exceptionClassArray = [];
	}

	/** @param class-string<ValidationException> $exceptionClass */
	public function add(
		Element $element,
		string $errorMessage,
		string $exceptionClass = ValidityStateException::class,
	):void {
		$name = $element->getAttribute("name");

		if(!isset($this->errorArray[$name])) {
			$this->errorArray[$name] = [];
			$this->exceptionClassArray[$name] = [];
		}

		array_push($this->errorArray[$name], $errorMessage);
		array_push($this->exceptionClassArray[$name], $exceptionClass);
	}

	public function count():int {
		return count($this->errorArray);
	}

	public function rewind():void {
		$this->iteratorKey = 0;
	}

	public function valid():bool {
		$keys = array_keys($this->errorArray);
		return isset($keys[$this->iteratorKey]);
	}

	public function current():string {
		$keys = array_keys($this->errorArray);
		return implode(
			"; ",
			array_unique($this->errorArray[$keys[$this->iteratorKey]] ?? [])
		);
	}

	public function next():void {
		$this->iteratorKey++;
	}

	public function key():string {
		$keys = array_keys($this->errorArray);
		return $keys[$this->iteratorKey];
	}

	/** @return list<class-string<ValidationException>> */
	public function getExceptionClassList():array {
		$exceptionClassList = [];

		foreach($this->exceptionClassArray as $exceptionClassArray) {
			foreach($exceptionClassArray as $exceptionClass) {
				if(!in_array($exceptionClass, $exceptionClassList, true)) {
					array_push($exceptionClassList, $exceptionClass);
				}
			}
		}

		return $exceptionClassList;
	}
}
