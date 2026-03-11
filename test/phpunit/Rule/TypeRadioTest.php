<?php
namespace Gt\DomValidation\Test\Rule;

use Gt\Dom\Element;
use Gt\Dom\ElementType;
use Gt\Dom\HTMLDocument;
use Gt\DomValidation\Rule\TypeRadio;
use Gt\DomValidation\Test\Helper\Helper;
use Gt\DomValidation\ValidationException;
use Gt\DomValidation\Validator;
use PHPUnit\Framework\TestCase;

class TypeRadioTest extends TestCase {
	public function testRadio():void {
		$document = new HTMLDocument(Helper::HTML_RADIO);
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

	public function testRadioMissingRequired():void {
		$document = new HTMLDocument(Helper::HTML_RADIO);
		$form = $document->forms[0];
		$validator = new Validator();

		try {
			$validator->validate($form, [
				"sort" => "asc",
			]);
		}
		catch(ValidationException) {
			$errorArray = iterator_to_array($validator->getLastErrorList());
			self::assertSame(
				"This field is required",
				$errorArray["currency"],
			);
		}
	}

	public function testRadioTextContent():void {
		$document = new HTMLDocument(Helper::HTML_RADIO);
		$form = $document->forms[0];
		$validator = new Validator();

		$exception = null;

		try {
			$validator->validate($form, [
				"currency" => "USD",
				"sort" => "desc",
			]);
		}
		catch(ValidationException $exception) {
		}

		self::assertNull($exception);
	}

	public function testRadioTextContentInvalid():void {
		$document = new HTMLDocument(Helper::HTML_RADIO);
		$form = $document->forms[0];
		$validator = new Validator();

		try {
			$validator->validate($form, [
				"currency" => "USD",
				"sort" => "rand", // This <option> does not exist
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

	public function testIsValid_noForm():void {
		$document = new HTMLDocument();
		$element = $document->createElement("input");
		$element->type = "radio";
		$sut = new TypeRadio();

		$validity = $sut->isValid($element, "anything", []);
		self::assertTrue($validity);
	}
}
