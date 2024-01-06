<?php
namespace Gt\DomValidation\Test\Helper;

class Helper {
	const HTML_USERNAME_PASSWORD = <<<HTML
<!doctype html>
<form method="post">
	<label>
		<span>Username</span>
		<input name="username" required />
	</label>
	<label>
		<span>Password</span>
		<input name="password" type="password" minlength="12" required />
	</label>
	<button name="do" value="login">Log in!</button>
</form>
HTML;

	const HTML_PATTERN_CREDIT_CARD = <<<HTML
<!doctype html>
<form method="post">
	<label>
		<span>Your name</span>
		<input name="name" required />	
	</label>
	<label>
		<span>Credit Card number</span>
		<input name="credit-card" pattern="\d{16}" />
	</label>
	<label>
		<span>Expiry month</span>
		<input name="expiry-month" type="number" min="1" max="12" />	
	</label>
	<label>
		<span>Expiry year</span>
		<input name="expiry-year" type="number" min="18" max="26" />
	</label>
	<label>
		<span>amount</span>
		<input name="amount" type="number" />
	</label>
	<button name="do" value="buy">Buy the thing!</button>
</form>
HTML;

	const HTML_PATTERN_CREDIT_CARD_ALL_REQUIRED_FIELDS = <<<HTML
<!doctype html>
<form method="post">
	<label>
		<span>Your name</span>
		<input name="name" required />	
	</label>
	<label>
		<span>Credit Card number</span>
		<input name="credit-card" pattern="(\d\s?){16}" title="The 16 digit number on the front of your card" required />
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
		<input name="amount" type="number" required />
	</label>
	<button name="do" value="buy">Buy the thing!</button>
</form>
HTML;

	const HTML_STEP_NUMBERS = <<<HTML
<!doctype html>
<form method="post">
	<label>
		<span>Basic step of 7</span>
		<input name="step1" type="number" step="7" />
	</label>
	<label>
		<span>Step of 7, starting from 2</span>
		<input name="step2" type="number" step="7" min="2" />
	</label>
	<label>
		<span>Step of 7.2 (max 3 and a bit steps up)</span>
		<input name="step3" type="range" step="7.2" max="25.1" />
	</label>
	<label>
		<span>Step of 7.2, starting from 3.5 (max 3 steps up)</span>
		<input name="step4" type="range" step="7.2" min="3.5" max="25.1" />
	</label>
</form>
HTML;

	const HTML_USER_PROFILE = <<<HTML
<!doctype html>
<form method="post">
	<label>
		<span>Your email</span>
		<input name="email" type="email" />
	</label>
	<label>
		<span>Your password</span>
		<input name="password" type="password" />
	</label>
	<label>
		<span>Your website</span>
		<input name="website" type="url" />
	</label>
	<label>
		<span>Your date of birth</span>
		<input name="dob" type="date" />
	</label>
</form>
HTML;

	const HTML_DATE_TIME = <<<HTML
<!doctype html>
<form method="post">
	<label>
		<span>Starting date</span>
		<input name="date" type="date" />
	</label>
	<label>
		<span>Month of activity</span>
		<input name="month" type="month" />
	</label>
	<label>
		<span>Week of holiday</span>
		<input name="week" type="week" />
	</label>
	<label>
		<span>Time of daily reset</span>
		<input name="time" type="time" />
	</label>
	<label>
		<span>Payment date and time</span>
		<input name="datetime" type="datetime-local" />
	</label>
</form>
HTML;

	const HTML_SELECT = <<<HTML
<!doctype html>
<form method="post">
	<label>
		<span>Currency</span>
		<select name="currency" required>
			<option></option>
			<option value="GBP">£ Pound (GBP)</option>		
			<option value="USD">$ Dollar (USD)</option>		
			<option value="EUR">€ Euro (EUR)</option>		
		</select>	
	</label>
	
	<label>
		<span>Sort order</span>
		<select name="sort">
			<option>Ascending</option>		
			<option>Descending</option>		
		</select>
	</label>
	
	<label>
		<span>Show connections</span>
		<select name="connections">
			<option value="">All</option>
			<option value="native">Native only</option>
			<option value="complex">Complex only</option>
		</select>
	</label>
</form>
HTML;

	const HTML_RADIO = <<<HTML
<!doctype html>
<form method="post">
	<fieldset>
		<legend>Currency</legend>
		<label>
			<input type="radio" name="currency" value="GBP" required />
			<span>£ Pound (GBP)</span>
		</label>
		<label>
			<input type="radio" name="currency" value="USD" required />
			<span>$ Dollar (USD)</span>
		</label>
		<label>
			<input type="radio" name="currency" value="EUR" required />
			<span>€ Pound (EUR)</span>
		</label>
	</fieldset>
	
	<fieldset>
		<legend>Sort order</legend>
		<label>
			<input type="radio" name="sort" value="asc" />
			<span>Ascending</span>
		</label>
		<label>
			<input type="radio" name="sort" value="desc" />
			<span>Descending</span>
		</label>
	</fieldset>
	
	<fieldset>
		<legend>Show connections</legend>
		<label>
			<input type="radio" name="connections" value="" />
			<span>All</span>		
		</label>
		<label>
			<input type="radio" name="connections" value="native" />
			<span>Native only</span>		
		</label>
		<label>
			<input type="radio" name="connections" value="complex" />
			<span>Complex only</span>		
		</label>
	</fieldset>
	
	<button>Submit</button>
</form>
HTML;

	const HTML_CHECKBOX = <<<HTML
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

	const HTML_TWITTER = <<<HTML
<!doctype html>
<form method="post">
	<label>
		<span>What's happening?</span>
		<!-- The following textarea is arbitrarily limited to 120 
		characters to dissuade any form of intelligent discourse. -->
		<textarea name="tweet" maxlength="120"></textarea>
	</label>
	<button name="do" value="tweet">Tweet</button>
</form>
HTML;


}
