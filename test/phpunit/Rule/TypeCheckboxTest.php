<?php
namespace Gt\DomValidation\Test\Rule;

use Gt\Dom\Element;
use Gt\Dom\ElementType;
use Gt\Dom\HTMLDocument;
use Gt\DomValidation\Rule\TypeCheckbox;
use Gt\DomValidation\Rule\TypeRadio;
use Gt\DomValidation\Test\Helper\Helper;
use Gt\DomValidation\ValidationException;
use Gt\DomValidation\Validator;
use PHPUnit\Framework\TestCase;

class TypeCheckboxTest extends TestCase {
	public function testCheckbox():void {
		$document = new HTMLDocument(Helper::HTML_CHECKBOX);
		$form = $document->forms[0];
		$validator = new Validator();

		$exception = null;

		try {
			$validator->validate($form, []);
		}
		catch(ValidationException $exception) {
		}

		self::assertNull($exception);
	}

	public function testCheckboxTextContentInvalid():void {
		$document = new HTMLDocument(Helper::HTML_CHECKBOX);
		$form = $document->forms[0];
		$validator = new Validator();

		try {
			$validator->validate($form, [
				"currency" => ["Strawpenny"],
			]);
		}
		catch(ValidationException) {
			$errorArray = iterator_to_array($validator->getLastErrorList());
			self::assertCount(1, $errorArray);
			self::assertSame(
				"This field's value must match one of the available options",
				$errorArray["currency[]"]
			);
		}
	}

	public function testCheckboxTextContentInvalid_withValidSelections():void {
		$document = new HTMLDocument(Helper::HTML_CHECKBOX);
		$form = $document->forms[0];
		$validator = new Validator();

		$exception = null;

		try {
			$validator->validate($form, [
				"currency" => ["GBP"],
			]);
		}
		catch(ValidationException $exception) {
			var_dump($validator->getLastErrorList());die();
		}

		self::assertNull($exception);
	}

	public function testIsValid_noForm():void {
		$document = new HTMLDocument();
		$element = $document->createElement("input");
		$element->type = "checkbox";
		$sut = new TypeCheckbox();

		$validity = $sut->isValid($element, "anything", []);
		self::assertTrue($validity);
	}
}
