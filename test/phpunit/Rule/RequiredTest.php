<?php
namespace GT\DomValidation\Test\Rule;

use GT\Dom\HTMLDocument;
use GT\DomValidation\Test\DomValidationTestCase;
use GT\DomValidation\Test\Helper\Helper;
use GT\DomValidation\ValidationException;
use GT\DomValidation\Validator;
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
				self::assertSame("This field is required", $error);
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
				self::assertSame("This field is required", $error);
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

	public function testIsValid_zeroIsNotMissing():void {
		$document = new HTMLDocument('<form><input name="text" required></form>');
		$element = $document->querySelector("input");
		$sut = new \GT\DomValidation\Rule\Required();

		self::assertTrue($sut->isValid($element, "0", []));
		self::assertFalse($sut->isValid($element, "", []));
	}
}
