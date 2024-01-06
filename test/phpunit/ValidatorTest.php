<?php
namespace Gt\DomValidation\Test;

use ArrayIterator;
use Gt\Dom\Element;
use Gt\Dom\HTMLDocument;
use Gt\Dom\HTMLElement;
use Gt\DomValidation\DefaultValidationRules;
use Gt\DomValidation\Rule\Rule;
use Gt\DomValidation\Test\Helper\Helper;
use Gt\DomValidation\ValidationException;
use Gt\DomValidation\Validator;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use stdClass;

class ValidatorTest extends TestCase {
	public function testSimpleValidInput() {
		$document = new HTMLDocument(Helper::HTML_USERNAME_PASSWORD);
		$form = $document->forms[0];
		$validator = new Validator();

		$exception = null;

		try {
			$validator->validate($form, [
				"username" => "g105b",
				"password" => "hunter222222",
			]);
		}
		catch(ValidationException $exception) {
		}

		self::assertNull($exception);
	}

	/**
	 * In this example, the password field will be invalid if it contains
	 * the username.
	 */
	public function testCustomRule() {
		$usernameNotWithinPasswordRule = new class extends Rule {
			public function isValid(Element $element, string|array $value, array $inputKvp):bool {
				if($element->type !== "password") {
					return true;
				}

				return !str_contains($value, $inputKvp["username"]);
			}

			public function getHint(Element $element, string $value):string {
				return "The password must not contain the username";
			}
		};

		$document = new HTMLDocument(Helper::HTML_USERNAME_PASSWORD);
		$form = $document->forms[0];
		$rules = new DefaultValidationRules();
		$rules->addRule($usernameNotWithinPasswordRule);
		$validator = new Validator($rules);

		$exception = null;

		try {
			$validator->validate($form, [
				"username" => "g105b",
				"password" => "g105b-password",
			]);
		}
		catch(ValidationException $exception) {
			$errorArray = iterator_to_array($validator->getLastErrorList());
			self::assertCount(1, $errorArray);
			self::assertSame(
				"The password must not contain the username",
				$errorArray["password"],
			);
		}

		self::assertNotNull($exception);
	}

	public function testValidate_objectAsArray():void {
		$kvpInput = [
			"name" => "Andrew Chi-Chih Yao",
			"dob" => "1946-12-24",
		];

		/** @var ArrayIterator<string, string>|MockObject $inputObject */
		$inputObject = self::getMockBuilder(ArrayIterator::class)
			->addMethods(["asArray"])
			->getMock();
		$inputObject->expects(self::once())
			->method("asArray")
			->willReturn($kvpInput);

		$form = self::createMock(Element::class);

		$sut = new Validator();
		$sut->validate($form, $inputObject);
	}

	public function testValidate_kvpObject():void {
		$document = new HTMLDocument(Helper::HTML_USERNAME_PASSWORD);
		$form = $document->forms[0];
		$validator = new Validator();
		$input = new stdClass();
		$input->username = "g105b";
		$input->password = "hunter222222";

		$exception = null;

		try {
			$validator->validate($form, $input);
		}
		catch(ValidationException $exception) {}

		self::assertNull($exception);
	}

	public function testValidate_kvpTraversible():void {
		$document = new HTMLDocument(Helper::HTML_USERNAME_PASSWORD);
		$form = $document->forms[0];
		$validator = new Validator();
		$input = new class([
			"username" => "g105b",
			"password" => "hunter222222",
		]) extends ArrayIterator {};

		$exception = null;

		try {
			$validator->validate($form, $input);
		}
		catch(ValidationException $exception) {}

		self::assertNull($exception);
	}

	public function testValidate_kvpObject_notStringable():void {
		$document = new HTMLDocument(Helper::HTML_USERNAME_PASSWORD);
		$form = $document->forms[0];
		$validator = new Validator();
		$input = new stdClass();
		$input->username = "g105b";
		$input->password = "hunter222222";
		$input->somethingElse = new stdClass();

		$exception = null;

		try {
			$validator->validate($form, $input);
		}
		catch(ValidationException $exception) {}

		self::assertNull($exception);
	}
}
