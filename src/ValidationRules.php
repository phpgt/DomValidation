<?php
namespace GT\DomValidation;

use GT\DomValidation\Rule\Rule;

abstract class ValidationRules {
	/** @var Rule[] */
	protected array $ruleList;

	public function __construct() {
		$this->setRuleList();
	}

	/** Must instantiate $this->ruleList as an array of Rule objects */
	abstract protected function setRuleList():void;

	public function addRule(Rule $rule):void {
		array_push($this->ruleList, $rule);
	}

	/**
	 * Returns an associative array of rules affected by each attribute.
	 * The key of the array is the DOMElement attribute name that affects
	 * the rule(s). The value of the array is an array of Rule objects.
	 *
	 * @return array<string, Rule[]>
	 */
	public function getAttributeRuleList():array {
		$attributeRuleList = [];

		foreach($this->ruleList as $rule) {
			foreach($rule->getAttributes() as $attrString) {
				if(!isset($attributeRuleList[$attrString])) {
					$attributeRuleList[$attrString] = [];
				}

				array_push($attributeRuleList[$attrString], $rule);
			}
		}

		return $attributeRuleList;
	}
}
