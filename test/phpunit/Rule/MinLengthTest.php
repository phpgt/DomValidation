<?php
namespace Gt\DomValidation\Test\Rule;

use Gt\Dom\HTMLDocument;
use Gt\DomValidation\Test\Helper\Helper;
use Gt\DomValidation\ValidationException;
use Gt\DomValidation\Validator;
use PHPUnit\Framework\TestCase;

class MinLengthTest extends TestCase {
	public function testMinLengthOK():void {
		$document = new HTMLDocument(Helper::HTML_USERNAME_PASSWORD);
		$form = $document->forms[0];
		$validator = new Validator();

		$exception = null;
		try {
			$validator->validate($form, [
				"username" => "mikebrewer",
				"password" => "holdoutyerhand1964",
			]);
		}
		catch(ValidationException $exception) {
		}

		self::assertNull($exception);
	}

	public function testMinLength():void {
		$document = new HTMLDocument(Helper::HTML_USERNAME_PASSWORD);
		$form = $document->forms[0];
		$validator = new Validator();

		$exception = null;
		try {
			$validator->validate($form, [
				"username" => "mikebrewer",
				"password" => "mb1964",
			]);
		}
		catch(ValidationException $exception) {
			$errorList = iterator_to_array($validator->getLastErrorList());
			self::assertSame(
				"This field's value must contain at least 12 characters",
				$errorList["password"]
			);
		}
	}
}

