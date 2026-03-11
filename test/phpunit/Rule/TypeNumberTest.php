<?php
namespace Gt\DomValidation\Test\Rule;

use Gt\Dom\Element;
use Gt\Dom\HTMLDocument;
use Gt\DomValidation\Rule\TypeNumber;
use Gt\DomValidation\Test\Helper\Helper;
use Gt\DomValidation\ValidationException;
use Gt\DomValidation\Validator;
use PHPUnit\Framework\TestCase;

class TypeNumberTest extends TestCase {
	public function testNumberFieldEmpty():void {
		$document = new HTMLDocument(Helper::HTML_PATTERN_CREDIT_CARD);
		$form = $document->forms[0];
		$validator = new Validator();

		$exception = null;

		try {
			$validator->validate($form, [
				"name" => "Jeff Bezos",
				"credit-card" => "4921166184521652",
				"expiry-month" => "",
				"expiry-year" => "",
				"amount" => "",
			]);
		}
		catch(ValidationException $exception) {
		}

		self::assertNull($exception);
	}

	public function testNumberField():void {
		$document = new HTMLDocument(Helper::HTML_PATTERN_CREDIT_CARD);
		$form = $document->forms[0];
		$validator = new Validator();

		$exception = null;

		try {
			$validator->validate($form, [
				"name" => "Jeff Bezos",
				"credit-card" => "4921166184521652",
				"expiry-month" => "01",
				"expiry-year" => "22",
				"amount" => "100",
			]);
		}
		catch(ValidationException $exception) {
		}

		self::assertNull($exception);
	}

	public function testNumberFieldFloat():void {
		$document = new HTMLDocument(Helper::HTML_PATTERN_CREDIT_CARD);
		$form = $document->forms[0];
		$validator = new Validator();

		$exception = null;

		try {
			$validator->validate($form, [
				"name" => "Jeff Bezos",
				"credit-card" => "4921166184521652",
				"expiry-month" => "01",
// contrary to this making sense, it is actually valid without a "step":
				"expiry-year" => "22.345",
				"amount" => "100",
			]);
		}
		catch(ValidationException $exception) {
		}

		self::assertNull($exception);
	}

	public function testNumberFieldNotANumber():void {
		$document = new HTMLDocument(Helper::HTML_PATTERN_CREDIT_CARD);
		$form = $document->forms[0];
		$validator = new Validator();

		try {
			$validator->validate($form, [
				"name" => "Jeff Bezos",
				"credit-card" => "4921166184521652",
				"expiry-month" => "January",
				"expiry-year" => "22",
				"amount" => "100",
			]);
		}
		catch(ValidationException) {
			$errorArray = iterator_to_array($validator->getLastErrorList());
			self::assertCount(1, $errorArray);
			self::assertSame(
				"Field must be a number",
				$errorArray["expiry-month"]
			);
		}
	}

	public function testStep():void {
		$document = new HTMLDocument(Helper::HTML_STEP_NUMBERS);
		$form = $document->forms[0];
		$validator = new Validator();

		$exception = null;

		try {
			$validator->validate($form, [
				"step1" => "14",
			]);
		}
		catch(ValidationException $exception) {
		}

		self::assertNull($exception);
	}

	public function testStepInvalid():void {
		$document = new HTMLDocument(Helper::HTML_STEP_NUMBERS);
		$form = $document->forms[0];
		$validator = new Validator();

		try {
			$validator->validate($form, [
				"step1" => "15",
			]);
		}
		catch(ValidationException $exception) {
			$errorArray = iterator_to_array($validator->getLastErrorList());
			self::assertCount(1, $errorArray);
			self::assertSame(
				"Field value must be a multiple of 7",
				$errorArray["step1"]
			);
		}
	}

	public function testStepNegative():void {
		$document = new HTMLDocument(Helper::HTML_STEP_NUMBERS);
		$form = $document->forms[0];
		$validator = new Validator();

		$exception = null;

		try {
			$validator->validate($form, [
				"step1" => "-21",
			]);
		}
		catch(ValidationException $exception) {
		}

		self::assertNull($exception);
	}

	public function testStepStartingFrom2():void {
		$document = new HTMLDocument(Helper::HTML_STEP_NUMBERS);
		$form = $document->forms[0];
		$validator = new Validator();

		$exception = null;

		try {
			$validator->validate($form, [
				"step2" => "16",
			]);
		}
		catch(ValidationException $exception) {
		}

		self::assertNull($exception);
	}

	public function testStepStartingFrom2Invalid():void {
		$document = new HTMLDocument(Helper::HTML_STEP_NUMBERS);
		$form = $document->forms[0];
		$validator = new Validator();

		try {
			$validator->validate($form, [
				"step2" => "21",
			]);
		}
		catch(ValidationException $exception) {
			$errorArray = iterator_to_array($validator->getLastErrorList());
			self::assertCount(1, $errorArray);
			self::assertSame(
				"Field value must be 2 plus a multiple of 7",
				$errorArray["step2"]
			);
		}
	}

	public function testStepWithMinBust():void {
		$document = new HTMLDocument(Helper::HTML_STEP_NUMBERS);
		$form = $document->forms[0];
		$validator = new Validator();

		try {
			$validator->validate($form, [
				"step2" => "-4",
			]);
		}
		catch(ValidationException) {
			$errorArray = iterator_to_array($validator->getLastErrorList());
			self::assertCount(1, $errorArray);
			self::assertSame(
				"Field value must not be lower than 2",
				$errorArray["step2"]
			);
		}
	}

	public function testStepWithMax():void {
		$document = new HTMLDocument(Helper::HTML_STEP_NUMBERS);
		$form = $document->forms[0];
		$validator = new Validator();

		$exception = null;

		try {
			$validator->validate($form, [
				"step3" => 7.2 * 3, // within range
			]);
		}
		catch(ValidationException $exception) {}

		self::assertNull($exception);
	}

	public function testStepWithMaxBust():void {
		$document = new HTMLDocument(Helper::HTML_STEP_NUMBERS);
		$form = $document->forms[0];
		$validator = new Validator();

		try {
			$validator->validate($form, [
				"step3" => 7.2 * 4, // out of range
			]);
		}
		catch(ValidationException $exception) {
			$errorArray = iterator_to_array($validator->getLastErrorList());
			self::assertCount(1, $errorArray);
			self::assertSame(
				"Field value must not be higher than 25.1",
				$errorArray["step3"]
			);
		}
	}

	public function testStepWithDecimalStart():void {
		$document = new HTMLDocument(Helper::HTML_STEP_NUMBERS);
		$form = $document->forms[0];
		$validator = new Validator();

		$exception = null;

		try {
			$validator->validate($form, [
				"step4" => 3.5 + (7.2 * 2),
			]);
		}
		catch(ValidationException $exception) {
		}

		self::assertNull($exception);
	}

	public function testStepWithDecimalStartBust():void {
		$document = new HTMLDocument(Helper::HTML_STEP_NUMBERS);
		$form = $document->forms[0];
		$validator = new Validator();

		try {
			$validator->validate($form, [
				"step4" => 3.5 + (7.2 * 4),
			]);
		}
		catch(ValidationException) {
			$errorArray = iterator_to_array($validator->getLastErrorList());
			self::assertSame(
				"Field value must not be higher than 25.1",
				$errorArray["step4"]
			);
		}
	}

	public function testGetHint_ok():void {
		$element = self::createMock(Element::class);
		$element->method("getAttribute")
			->willReturn(null);

		$sut = new TypeNumber();
		self::assertEmpty($sut->getHint($element, "1"));
	}
}
