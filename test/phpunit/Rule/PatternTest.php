<?php
namespace Gt\DomValidation\Test\Rule;

use Gt\Dom\HTMLDocument;
use Gt\DomValidation\Test\Helper\Helper;
use Gt\DomValidation\ValidationException;
use Gt\DomValidation\Validator;
use PHPUnit\Framework\TestCase;

class PatternTest extends TestCase {
	public function testPattern():void {
		$document = new HTMLDocument(Helper::HTML_PATTERN_CREDIT_CARD);
		$form = $document->forms[0];
		$validator = new Validator();

		$exception = null;

		try {
			$validator->validate($form, [
				"name" => "Jeff Bezos",
				"credit-card" => "4921166184521652",
				"expiry-year" => 20,
				"expiry-month" => 12,
			]);
		}
		catch(ValidationException $exception) {
		}

		self::assertNull($exception);
	}

	public function testPatternInvalid():void {
		$document = new HTMLDocument(Helper::HTML_PATTERN_CREDIT_CARD);
		$form = $document->forms[0];
		$validator = new Validator();

		$exception = null;

		try {
			$validator->validate($form, [
				"name" => "Jeff Bezos",
				"credit-card" => "492116611652", // not enough numbers
			]);
		}
		catch(ValidationException $exception) {
			$errorArray = iterator_to_array($validator->getLastErrorList());
			self::assertCount(1, $errorArray);
			$creditCardError = $errorArray["credit-card"];
			self::assertSame(
				"This field does not match the required pattern",
				$creditCardError
			);
		}
	}

	public function testPatternWithMissingRequiredFields():void {
		$document = new HTMLDocument(Helper::HTML_PATTERN_CREDIT_CARD_ALL_REQUIRED_FIELDS);
		$form = $document->forms[0];
		$validator = new Validator();

		try {
			$validator->validate($form, [
				"name" => "Jeff Bezos",
				"credit-card" => "4921166184521652",
			]);
		}
		catch(ValidationException $exception) {
			$errorArray = iterator_to_array($validator->getLastErrorList());
			self::assertCount(3, $errorArray);
			self::assertEquals($errorArray["expiry-month"], "This field is required");
			self::assertEquals($errorArray["expiry-year"], "This field is required");
			self::assertEquals($errorArray["amount"], "This field is required");
		}
	}

	public function testPatternTitleShown():void {
		$document = new HTMLDocument(Helper::HTML_PATTERN_CREDIT_CARD_ALL_REQUIRED_FIELDS);
		$form = $document->forms[0];
		$validator = new Validator();

		try {
			$validator->validate($form, [
				"name" => "Elon Musk",
				"creit-card" => "420420"
			]);
		}
		catch(ValidationException $exception) {
			$errorArray = iterator_to_array($validator->getLastErrorList());
			self::assertSame(
				"This field is required; This field does not match the required pattern: The 16 digit number on the front of your card",
				$errorArray["credit-card"]
			);
		}
	}
}
