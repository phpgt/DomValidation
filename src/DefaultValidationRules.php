<?php
namespace GT\DomValidation;

use GT\DomValidation\Rule\MaxLength;
use GT\DomValidation\Rule\MinLength;
use GT\DomValidation\Rule\Pattern;
use GT\DomValidation\Rule\TypeCheckbox;
use GT\DomValidation\Rule\TypeRadio;
use GT\DomValidation\Rule\Required;
use GT\DomValidation\Rule\SelectElement;
use GT\DomValidation\Rule\TypeDate;
use GT\DomValidation\Rule\TypeEmail;
use GT\DomValidation\Rule\TypeNumber;
use GT\DomValidation\Rule\TypeUrl;

class DefaultValidationRules extends ValidationRules {
	protected function setRuleList():void {
		$this->ruleList = [
			new Required(),
			new Pattern(),
			new TypeNumber(),
			new TypeEmail(),
			new TypeUrl(),
			new TypeDate(),
			new SelectElement(),
			new MinLength(),
			new MaxLength(),
			new TypeRadio(),
			new TypeCheckbox(),
		];
	}
}
