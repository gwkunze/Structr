<?php

namespace Structr\Tree\Scalar;

use Structr\Tree\Base\ScalarNode;

use Structr\Exception;

class StringNode extends ScalarNode {
	private $regexp = null;
	private $enum = null;
	private $enum_case_insensitive = true;

	public function getScalarType() {
		return "string";
	}

	public function regexp($regexp) {
		$this->regexp = $regexp;
		
		return $this;
	}

	public function enum(array $enum, $case_insensitive = true) {
		$this->enum = $enum;
		$this->enum_case_insensitive = $case_insensitive;

		return $this;
	}

	protected function coerceValueFromObject($value, $strict) {
		if(is_callable(array($value, "__toString"))) {
			return (string)$value;
		}

		if($strict) {
			throw new Exception("Cannot coerce an object to a string in strict mode");
		}

		return "Object";
	}

	public function _walk_value($value = null) {
		$value = parent::_walk_value($value);

		if($this->regexp !== null && !preg_match($this->regexp, $value)) {
			throw new Exception("String did not match regular expression");
		}

		if($this->enum !== null) {
			$regexp = "/^((" . implode(")|(", array_map(function($item) { return preg_quote($item, "/"); } , $this->enum)) . "))$/" . (($this->enum_case_insensitive)?"i":"");
			if(!preg_match($regexp, $value))
				throw new Exception("'{$value}' not part of enum");
		}

		return $value;
	}
}
