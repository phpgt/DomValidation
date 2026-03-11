<?php
namespace Gt\DomValidation\Test\Rule;

use Gt\Dom\HTMLDocument;
use Gt\DomValidation\Test\Helper\Helper;
use Gt\DomValidation\ValidationException;
use Gt\DomValidation\Validator;
use PHPUnit\Framework\TestCase;

class UrlTypeTest extends TestCase {
	public function testUrl():void {
		$document = new HTMLDocument(Helper::HTML_USER_PROFILE);
		$form = $document->forms[0];
		$validator = new Validator();

		$exception = null;

		try {
			$validator->validate($form, [
				"website" => "https://www.php.gt",
			]);
		}
		catch(ValidationException $exception) {}

		self::assertNull($exception);
	}

// There's no need to test every permeation of invalid URLs,
// because we can be safe in the assumption that filter_var is already
// well tested.
	public function testUrlInvalid():void {
		$document = new HTMLDocument(Helper::HTML_USER_PROFILE);
		$form = $document->forms[0];
		$validator = new Validator();

		try {
			$validator->validate($form, [
				"website" => "https:www.php.gt",
			]);
		}
		catch(ValidationException) {
			$errorArray = iterator_to_array($validator->getLastErrorList());
			self::assertCount(1, $errorArray);
			self::assertSame(
				"Field must be a URL",
				$errorArray["website"]
			);
		}
	}
}
