<?php
namespace GT\DomValidation\Test\Rule;

use GT\Dom\HTMLDocument;
use GT\DomValidation\Test\Helper\Helper;
use GT\DomValidation\ValidationException;
use GT\DomValidation\Validator;
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

	public function testIsValid_utf16Length():void {
		$document = new HTMLDocument('<form><input name="text" minlength="2"></form>');
		$element = $document->querySelector("input");
		$sut = new \GT\DomValidation\Rule\MinLength();

		self::assertTrue($sut->isValid($element, "馬馬", []));
		self::assertTrue($sut->isValid($element, "😀", []));
		self::assertTrue($sut->isValid($element, "😀a", []));
		self::assertTrue($sut->isValid($element, "", []));
		self::assertFalse($sut->isValid($element, "a", []));
	}
}

