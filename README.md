Server side form validation using web standards.
------------------------------------------------

HTML forms can be annotated in such a way that the individual input elements can describe their own validation rules. The simplest annotation is the `required` attribute, which can be specified on input elements to indicate that the form is not to be submitted until a value is given.

This repository performs W3C form validation for projects that have a [server-side DOM][dom], such as within [WebEngine applications][webengine].

***

<a href="https://github.com/PhpGt/DomValidation/actions" target="_blank">
	<img src="https://badge.status.php.gt/domvalidation-build.svg" alt="Build status" />
</a>
<a href="https://app.codacy.com/gh/PhpGt/DomValidation" target="_blank">
	<img src="https://badge.status.php.gt/domvalidation-quality.svg" alt="Code quality" />
</a>
<a href="https://app.codecov.io/gh/PhpGt/DomValidation" target="_blank">
	<img src="https://badge.status.php.gt/domvalidation-coverage.svg" alt="Code coverage" />
</a>
<a href="https://packagist.org/packages/PhpGt/DomValidation" target="_blank">
	<img src="https://badge.status.php.gt/domvalidation-version.svg" alt="Current version" />
</a>
<a href="http://www.php.gt/domvalidation" target="_blank">
	<img src="https://badge.status.php.gt/domvalidation-docs.svg" alt="PHP.Gt/DomValidation documentation" />
</a>

## Example usage

```html
<form id="example-form" method="post">
	<label>
		<span>Your name</span>
		<input name="name" required />
	</label>
	<label>
		<span>Your email</span>
		<input name="email" type="email" required />
	</label>
	<label>
		<span>Your account ID</span>
		<input name="account" pattern="\S*\d{,3}" />
	</label>
	<label>
		<span>Your nation</span>
		<select name="nation" required>
			<option></option>
			<option>Oceania</option>
			<option>Eurasia</option>
			<option>Eastasia</option>
		</select>
	</label>
	<button>Submit</button>
</form>
```

The above HTML will be validated on the client as usual, but using the PHP below will also provide server-side validation without any additional validation logic to be written.

Validation rules present in the above HTML form:

+ `name` input is required to be not empty.
+ `email` input is required to be not empty, and must be a valid email address.
+ `account` input is not required, but when a value is submitted, it must match the provided regular expression (any number of non-whitespace characters followed by up to 3 numbers).
+ `nation` input must be one of the three enumerations present in the `<select>` element.

```php
use Gt\Dom\HTMLDocument;
use Gt\DomValidation\Validator;
use Gt\DomValidation\ValidationException;

// Assume this function is triggered when POST data arrives.
function handleSubmit($inputData) {
	$document = new HTMLDocument(file_get_contents("example-form.html"));
// First, obtain a reference to the form we wish to validate.
	$form = $document->querySelector("#example-form");
	$validator = new Validator();

	try {
// Within a try/catch, pass the form and the user input into the Validator.
		$validator->validate($form, $inputData);
	}
	catch(ValidationException) {
// If there are any validation errors, we can iterate over them to display
// to the page, and return early as to not action the user input.
		foreach($validator->getLastErrorList() as $name => $message) {
// Here we can add an attribute to the parent of the input, for displaying
// the error message using CSS, for example.
			$errorElement = $form->querySelector("[name=$name]");
			$errorElement->parentNode->dataset->validationError = $message;
		}
        
// Return early so user input isn't used when there are validation errors. 
		return;
	}

// Finally, if the input contains no validation errors, continue as usual.
	sendInputToDatabase($inputData);
}
```

## Supported validation mechanisms:

It's possible to add your own validation mechanism by extending the `FormValidator` class and overriding the necessary functions.

+ `required` - field can not be left blank
+ `pattern` - must match the provided regular expression
+ `type` - must match the provided inbuilt data type
+ `min` - for numerical inputs, the minimum allowed value
+ `max` - for numerical inputs, the maximum allowed value
+ `minlength` - the minimum number of characters allowed
+ `maxlength` - the maximum number of characters allowed
+ `step` - the granularity that is required

### Supported types:

+ `tel`
+ `url`
+ `email`
+ `date`
+ `month`
+ `week`
+ `time`
+ `datetime-local`
+ `number`
+ `range`

### Special element behaviour

When using `<select>` and `<input type="radio" />` elements, their contained options are used as validation enumerations, meaning that values that are not part of the contained options will throw validation errors.

[dom]: https://www.php.gt/dom
[webengine]: https://www.php.gt/webengine

# Proudly sponsored by

[JetBrains Open Source sponsorship program](https://www.jetbrains.com/community/opensource/)

[![JetBrains logo.](https://resources.jetbrains.com/storage/products/company/brand/logos/jetbrains.svg)](https://www.jetbrains.com/community/opensource/)
