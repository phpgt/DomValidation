<?php
namespace Gt\DomValidation\Test\Rule;

use Gt\Dom\HTMLDocument;
use Gt\DomValidation\Test\DomValidationTestCase;
use Gt\DomValidation\Test\Helper\Helper;
use Gt\DomValidation\ValidationException;
use Gt\DomValidation\Validator;
use PHPUnit\Framework\TestCase;

class RequiredTest extends TestCase {
	public function testSimpleMissingRequiredInput():void {
		$document = new HTMLDocument(Helper::HTML_USERNAME_PASSWORD);
		$form = $document->forms[0];
		$validator = new Validator();

		self::expectException(ValidationException::class);
		self::expectExceptionMessage("There is 1 invalid field");

		$validator->validate($form, [
			"username" => "g105b",
		]);
	}

	public function testSimpleMissingBothRequiredInputs():void {
		$document = new HTMLDocument(Helper::HTML_USERNAME_PASSWORD);
		$form = $document->forms[0];
		$validator = new Validator();

		self::expectException(ValidationException::class);
		self::expectExceptionMessage("There are 2 invalid fields");

		$validator->validate($form, ["something" => "nothing"]);
	}

	public function testSimpleMissingRequiredInputErrorList():void {
		$document = new HTMLDocument(Helper::HTML_USERNAME_PASSWORD);
		$form = $document->forms[0];
		$validator = new Validator();

		try {
			$validator->validate($form, ["username" => "g105b"]);
		}
		catch(ValidationException $exception) {
			foreach($validator->getLastErrorList() as $error) {
				self::assertSame("This field is required; This field's value must contain at least 12 characters", $error);
			}
		}
	}

	public function testSimpleEmptyRequiredInputErrorList():void {
		$document = new HTMLDocument(Helper::HTML_USERNAME_PASSWORD);
		$form = $document->forms[0];
		$validator = new Validator();

		try {
			$validator->validate($form, [
				"username" => "g105b",
				"password" => ""
			]);
		}
		catch(ValidationException $exception) {
			foreach($validator->getLastErrorList() as $name => $error) {
				self::assertSame("This field is required; This field's value must contain at least 12 characters", $error);
				self::assertSame("password", $name);
			}
		}
	}

	public function testNumberFieldRequiredButEmpty():void {
		$document = new HTMLDocument(Helper::HTML_PATTERN_CREDIT_CARD_ALL_REQUIRED_FIELDS);
		$form = $document->forms[0];
		$validator = new Validator();

		try {
			$validator->validate($form, [
				"name" => "Jeff Bezos",
				"credit-card" => "4921166184521652",
				"expiry-month" => "",
				"expiry-year" => "",
			]);
		}
		catch(ValidationException $exception) {
			$errorArray = iterator_to_array($validator->getLastErrorList());
			self::assertCount(3, $errorArray);
		}
	}
}
