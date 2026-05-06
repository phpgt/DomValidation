<?php
/**
 * To run this example, from a terminal navigate to the example directory and run:
 * php -S 0.0.0.0:8080
 * then visit http://localhost:8080/01-shop.php in your web browser.
 */

use GT\Dom\HTMLDocument;
use GT\DomValidation\ValidationException;
use GT\DomValidation\Validator;

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
<form method="post">
	<label>
		<span>Your name</span>
		<input name="name" required />
	</label>
	<label>
		<span>Credit Card number</span>
		<input name="credit-card" pattern="\d{16}" required />
	</label>
	<label>
		<span>Expiry month</span>
		<input name="expiry-month" type="number" min="1" max="12" required />
	</label>
	<label>
		<span>Expiry year</span>
		<input name="expiry-year" type="number" min="18" max="26" required />
	</label>
	<label>
		<span>amount</span>
		<input name="amount" type="number" value="100.50" readonly required />
	</label>
	<button name="do" value="buy">Buy the thing!</button>
</form>
HTML;

function example(HTMLDocument $document, array $input) {
	$validator = new Validator();
	$form = $document->forms[0];

	try {
		$validator->validate($form, $input);
	} catch(ValidationException $exception) {
		foreach($validator->getLastErrorList() as $name => $message) {
			$errorElement = $form->querySelector("[name=$name]");
			$errorElement->parentNode->dataset->validationError = $message;
		}
		return;
	}

	echo "Payment succeeded!";
	exit;
}

$document = new HTMLDocument($html);

if(isset($_POST["do"]) && $_POST["do"] === "buy") {
	example($document, $_POST);
}

echo $document;
