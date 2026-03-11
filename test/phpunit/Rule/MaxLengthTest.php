<?php
namespace Gt\DomValidation\Test\Rule;

use Gt\Dom\HTMLDocument;
use Gt\DomValidation\Test\Helper\Helper;
use Gt\DomValidation\ValidationException;
use Gt\DomValidation\Validator;
use PHPUnit\Framework\TestCase;

class MaxLengthTest extends TestCase {
	public function testMaxLengthOK():void {
		$document = new HTMLDocument(Helper::HTML_TWITTER);
		$form = $document->forms[0];
		$validator = new Validator();

		$exception = null;
		try {
			$validator->validate($form, [
				"tweet" => "A Tweet is is a short, unintelligent and meaningless string of characters thrown into an echo chamber somewhere online."
			]);
		}
		catch(ValidationException $exception) {
		}

		self::assertNull($exception);
	}

	public function testMaxLength():void {
		$document = new HTMLDocument(Helper::HTML_TWITTER);
		$form = $document->forms[0];
		$validator = new Validator();

		$exception = null;
		try {
			$validator->validate($form, [
				"tweet" => "If an element has its form control maxlength attribute specified, the attribute’s value must be a valid non-negative integer. If the attribute is specified and applying the rules for parsing non-negative integers to its value results in a number, then that number is the element’s maximum allowed value length. If the attribute is omitted or parsing its value results in an error, then there is no maximum allowed value length."
			]);
		}
		catch(ValidationException $exception) {
			$errorList = iterator_to_array($validator->getLastErrorList());
			self::assertSame(
				"This field's value must not contain more than 120 characters",
				$errorList["tweet"]
			);
		}
	}
}
