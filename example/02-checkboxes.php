<?php
/**
 * To run this example, from a terminal navigate to the example directory and run:
 * php -S 0.0.0.0:8080
 * then visit http://localhost:8080/02-checkboxes.php in your web browser.
 *
 * The purpose of this example is to show how an invalid state occurs when
 * the user hacks the client-side to allow submitting a field that doesn't exist
 * in the source HTML.
 */

use Gt\Dom\HTMLDocument;
use Gt\DomValidation\ValidationException;
use Gt\DomValidation\Validator;

require __DIR__ . "/../vendor/autoload.php";

$html = <<<HTML
<!doctype html>
<style>
    [data-validation-error] {
        border-left: 2px solid red;
    }
    [data-validation-error]::before {
        content: attr(data-validation-error);
        color: red;
        font-weight: bold;
    }
    label {
        display: block;
        padding: 1rem;
    }
    label span {
        display: block;
    }
</style>
<!doctype html>
<form method="post">
	<fieldset>
		<legend>Available currencies</legend>
		<label>
			<input type="checkbox" name="currency[]" value="GBP" />
			<span>£ Pound (GBP)</span>
		</label>
		<label>
			<input type="checkbox" name="currency[]" value="USD" />
			<span>$ Dollar (USD)</span>
		</label>
		<label>
			<input type="checkbox" name="currency[]" value="EUR" />
			<span>€ Pound (EUR)</span>
		</label>
	</fieldset>
		
	<button name="do" value="submit">Submit</button>
</form>
HTML;

function example(HTMLDocument $document, array $input) {
	$validator = new Validator();
	$form = $document->forms[0];

	try {
		$validator->validate($form, $input);
	}
	catch(ValidationException $exception) {
		foreach($validator->getLastErrorList() as $name => $message) {
			$errorElement = $form->querySelector("[name=$name]");
			$errorElement->parentNode->dataset->validationError = $message;
		}
		return;
	}

	echo "Currencies selected: ";
	echo implode(", ", $input["currency"]);
	exit;
}

$document = new HTMLDocument($html);

if(isset($_POST["do"]) && $_POST["do"] === "submit") {
	example($document, $_POST);
}

echo $document;
