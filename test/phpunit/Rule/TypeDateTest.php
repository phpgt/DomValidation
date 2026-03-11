<?php
namespace Gt\DomValidation\Test\Rule;

use Gt\Dom\HTMLDocument;
use Gt\DomValidation\Test\Helper\Helper;
use Gt\DomValidation\ValidationException;
use Gt\DomValidation\Validator;
use PHPUnit\Framework\TestCase;

class TypeDateTest extends TestCase {
	public function testTypeDate():void {
		$document = new HTMLDocument(Helper::HTML_USER_PROFILE);
		$form = $document->forms[0];
		$validator = new Validator();

		$exception = null;

		try {
			$validator->validate($form, [
				"dob" => "1968-11-22",
			]);
		}
		catch(ValidationException $exception) {
		}

		self::assertNull($exception);
	}

	public function testTypeDateInvalid():void {
		$document = new HTMLDocument(Helper::HTML_USER_PROFILE);
		$form = $document->forms[0];
		$validator = new Validator();

		try {
			$validator->validate($form, [
				"dob" => "November 22nd 1968",
			]);
		}
		catch(ValidationException $exception) {
			$errorArray = iterator_to_array($validator->getLastErrorList());
			self::assertCount(1, $errorArray);
			self::assertSame(
				"Field must be a date in the format Y-m-d",
				$errorArray["dob"]
			);
		}
	}

	public function testTypeMonth():void {
		$document = new HTMLDocument(Helper::HTML_DATE_TIME);
		$form = $document->forms[0];
		$validator = new Validator();

		$exception = null;

		try {
			$validator->validate($form, [
				"month" => "2020-11",
			]);
		}
		catch(ValidationException $exception) {
		}

		self::assertNull($exception);
	}

	public function testTypeMonthInvalid():void {
		$document = new HTMLDocument(Helper::HTML_DATE_TIME);
		$form = $document->forms[0];
		$validator = new Validator();

		try {
			$validator->validate($form, [
				"month" => "November 2020",
			]);
		}
		catch(ValidationException $exception) {
			$errorArray = iterator_to_array($validator->getLastErrorList());
			self::assertCount(1, $errorArray);
			self::assertSame(
				"Field must be a month in the format Y-m",
				$errorArray["month"]
			);
		}
	}

	public function testTypeWeek():void {
		$document = new HTMLDocument(Helper::HTML_DATE_TIME);
		$form = $document->forms[0];
		$validator = new Validator();

		$exception = null;

		try {
			$validator->validate($form, [
				"week" => "2021-W24",
			]);
		}
		catch(ValidationException $exception) {
		}

		self::assertNull($exception);
	}

	public function testTypeWeekInvalid():void {
		$document = new HTMLDocument(Helper::HTML_DATE_TIME);
		$form = $document->forms[0];
		$validator = new Validator();

		try {
			$validator->validate($form, [
				"week" => "2021, Week 24",
			]);
		}
		catch(ValidationException $exception) {
			$errorArray = iterator_to_array($validator->getLastErrorList());
			self::assertCount(1, $errorArray);
			$monthErrorArray = $errorArray["week"];
			self::assertSame(
				"Field must be a week in the format Y-\WW",
				$monthErrorArray
			);
		}
	}

	public function testTypeWeekOutOfBounds():void {
		$document = new HTMLDocument(Helper::HTML_DATE_TIME);
		$form = $document->forms[0];
		$validator = new Validator();

		try {
			$validator->validate($form, [
				"week" => "2021-W55",
			]);
		}
		catch(ValidationException $exception) {
			$errorArray = iterator_to_array($validator->getLastErrorList());
			self::assertCount(1, $errorArray);
			self::assertSame(
				"Field must be a week in the format Y-\WW",
				$errorArray["week"]
			);
		}
	}

	public function testTypeDatetimeLocal():void {
		$document = new HTMLDocument(Helper::HTML_DATE_TIME);
		$form = $document->forms[0];
		$validator = new Validator();

		$exception = null;

		try {
			$validator->validate($form, [
				"datetime" => "2020-01-13T15:37",
			]);
		}
		catch(ValidationException $exception) {
		}

		self::assertNull($exception);
	}

	public function testTypeDatetimeLocalInvalid():void {
		$document = new HTMLDocument(Helper::HTML_DATE_TIME);
		$form = $document->forms[0];
		$validator = new Validator();

		try {
			$validator->validate($form, [
				"datetime" => "2020-01-13 15:37:00", // not using the correct ISO-8601 format
			]);
		}
		catch(ValidationException $exception) {
			$errorArray = iterator_to_array($validator->getLastErrorList());
			self::assertCount(1, $errorArray);
			self::assertSame(
				"Field must be a datetime-local in the format Y-m-d\TH:i",
				$errorArray["datetime"]
			);
		}
	}

	public function testTypeTime():void {
		$document = new HTMLDocument(Helper::HTML_DATE_TIME);
		$form = $document->forms[0];
		$validator = new Validator();

		$exception = null;

		try {
			$validator->validate($form, [
				"time" => "15:37",
			]);
		}
		catch(ValidationException $exception) {
		}

		self::assertNull($exception);
	}

	public function testTypeTimeInvalid():void {
		$document = new HTMLDocument(Helper::HTML_DATE_TIME);
		$form = $document->forms[0];
		$validator = new Validator();

		try {
			$validator->validate($form, [
				"time" => "3:37pm",
			]);
		}
		catch(ValidationException $exception) {
			$errorArray = iterator_to_array($validator->getLastErrorList());
			self::assertCount(1, $errorArray);
			self::assertSame(
				"Field must be a time in the format H:i",
				$errorArray["time"]
			);
		}
	}

	public function testTypeAttributeMissing():void {
		$document = new HTMLDocument("<form><input name='time' /></form>");
		$form = $document->forms[0];
		$validator = new Validator();

		$exception = null;
		try {
			$validator->validate($form, [
				"time" => "3:37pm",
			]);
		}
		catch(ValidationException $exception) {}
		self::assertNull($exception);
	}

	public function testTypeNotKnown():void {
		$document = new HTMLDocument(Helper::HTML_DATE_TIME);
		$form = $document->forms[0];
		$timeInput = $form->querySelector("[type='time']");
		$timeInput->type = "unknown";

		$validator = new Validator();

		$exception = null;

		try {
			$validator->validate($form, [
				"time" => "3:37pm",
			]);
		}
		catch(ValidationException $exception) {}

		self::assertNull($exception);
	}
}
