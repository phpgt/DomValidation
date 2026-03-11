<?php
namespace Gt\DomValidation\Test\Rule;

use Gt\Dom\HTMLDocument;
use Gt\DomValidation\Test\Helper\Helper;
use Gt\DomValidation\ValidationException;
use Gt\DomValidation\Validator;
use PHPUnit\Framework\TestCase;

class SelectElementTest extends TestCase {
	public function testSelect():void {
		$document = new HTMLDocument(Helper::HTML_SELECT);
		$form = $document->forms[0];
		$validator = new Validator();

		$exception = null;

		try {
			$validator->validate($form, [
				"currency" => "GBP",
			]);
		}
		catch(ValidationException $exception) {
		}

		self::assertNull($exception);
	}

	public function testSelectMissingRequired():void {
		$document = new HTMLDocument(Helper::HTML_SELECT);
		$form = $document->forms[0];
		$validator = new Validator();

		try {
			$validator->validate($form, [
				"currency" => "",
			]);
		}
		catch(ValidationException) {
			$errorArray = iterator_to_array($validator->getLastErrorList());
			self::assertCount(1, $errorArray);
			self::assertSame(
				"This field is required",
				$errorArray["currency"]
			);
		}
	}

	public function testSelectTextContent():void {
		$document = new HTMLDocument(Helper::HTML_SELECT);
		$form = $document->forms[0];
		$validator = new Validator();

		$exception = null;

		try {
			$validator->validate($form, [
				"currency" => "USD",
				"sort" => "Descending",
			]);
		}
		catch(ValidationException $exception) {
		}

		self::assertNull($exception);
	}

	public function testSelectTextContentInvalid():void {
		$document = new HTMLDocument(Helper::HTML_SELECT);
		$form = $document->forms[0];
		$validator = new Validator();

		try {
			$validator->validate($form, [
				"currency" => "USD",
				"sort" => "Random", // This <option> does not exist
			]);
		}
		catch(ValidationException) {
			$errorArray = iterator_to_array($validator->getLastErrorList());
			self::assertCount(1, $errorArray);
			self::assertSame(
				"This field's value must match one of the available options",
				$errorArray["sort"]
			);
		}
	}

	public function testSelectValue_textContent():void {
		$document = new HTMLDocument(Helper::HTML_SELECT);
		$form = $document->forms[0];
		$validator = new Validator();

		$exception = null;

		try {
			$validator->validate($form, [
				"currency" => "USD",
				"connections" => "All", // This is invalid
// There is an <option> with "All" as its text content, but not with this value.
			]);
		}
		catch(ValidationException $exception) {
		}

		self::assertSame("There is 1 invalid field", $exception->getMessage());
	}

	public function testSelectValue_invalid():void {
		$document = new HTMLDocument(Helper::HTML_SELECT);
		$form = $document->forms[0];
		$validator = new Validator();

		try {
			$validator->validate($form, [
				"currency" => "USD",
				"connections" => "Unknown", // This is invalid
			]);
		}
		catch(ValidationException $exception) {
			$errorArray = iterator_to_array($validator->getLastErrorList());
			self::assertCount(1, $errorArray);
			self::assertSame(
				"This field's value must match one of the available options",
				$errorArray["connections"]
			);
		}
	}

	public function testSelectTwoInvalidOptionsAndOneMissing():void {
		$document = new HTMLDocument(Helper::HTML_SELECT);
		$form = $document->forms[0];
		$validator = new Validator();

		try {
			$validator->validate($form, [
				"connections" => "none",
				"sort" => "random",
			]);
		}
		catch(ValidationException $exception) {
			$errorArray = iterator_to_array($validator->getLastErrorList());
			self::assertCount(3, $errorArray);
			self::assertSame(
				"This field is required",
				$errorArray["currency"]
			);
			self::assertSame(
				"This field's value must match one of the available options",
				$errorArray["sort"]
			);
			self::assertSame(
				"This field's value must match one of the available options",
				$errorArray["connections"]
			);
		}
	}
}
