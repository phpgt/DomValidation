<?php
namespace Gt\DomValidation\Rule;

use DateTime;
use Gt\Dom\Element;

class TypeDate extends Rule {
// ISO-8601 derived date formats:
	const FORMAT_DATE = "Y-m-d";
	const FORMAT_MONTH = "Y-m";
	const FORMAT_WEEK = "Y-\WW";
	const FORMAT_DATETIME_LOCAL = "Y-m-d\TH:i";
	const FORMAT_TIME = "H:i";

	protected array $attributes = [
		"type=date",
		"type=month",
		"type=week",
		"type=datetime-local",
		"type=time",
	];

	public function isValid(Element $element, string|array $value, array $inputKvp):bool {
		if($value === "") {
			return true;
		}

		$dateTime = $this->extractDateTime(
			$value,
			$element->getAttribute("type") ?? ""
		);

		return !is_null($dateTime);
	}

	public function getHint(Element $element, string $value):string {
		$format = null;
		$type = $element->getAttribute("type");

		switch($type) {
		case "date":
			$format = self::FORMAT_DATE;
			break;

		case "month":
			$format = self::FORMAT_MONTH;
			break;

		case "week":
			$format = self::FORMAT_WEEK;
			break;

		case "datetime-local":
			$format = self::FORMAT_DATETIME_LOCAL;
			break;

		case "time":
			$format = self::FORMAT_TIME;
			break;
		}

		return "Field must be a $type in the format $format";
	}

	private function extractDateTime(
		string $value,
		string $type,
	):?DateTime {
		if($type === "week") {
			return $this->extractDateTimeWeek($value);
		}
		else {
			$format = match($type) {
				"date" => self::FORMAT_DATE,
				"month" => self::FORMAT_MONTH,
				"datetime-local" => self::FORMAT_DATETIME_LOCAL,
				"time" => self::FORMAT_TIME,
				default => "",
			};

			return DateTime::createFromFormat($format, $value) ?: null;
		}
	}

	private function extractDateTimeWeek(string $value):?DateTime {
		$success = preg_match(
			"/^(?P<year>\d{4})-W(?P<week>\d{1,2})$/",
			$value,
			$matches
		);
		if(!$success) {
			return null;
		}

		if($matches["week"] < 1 || $matches["week"] > 52) {
			return null;
		}

		$dateTime = new DateTime();
		$dateTime->setISODate((int)$matches["year"], (int)$matches["week"]);
		return $dateTime;
	}
}
