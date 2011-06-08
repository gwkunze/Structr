<?php

namespace Structr\Value;

use \Structr\Exception\NoValueException;

class IndexValue extends Value {
	private $parent;
	private $index;

	public function __construct(Value $parent, $index) {
		$this->parent = $parent;
		$this->index = $index;
	}

	public function values() {
		$values = $this->parent->values();

		$return = array();
		foreach($values as $value) {
			if(isset($value[$this->index]))
				$return[] = $value[$this->index];
		}

		return $return;
	}

	public function parent() {
		return $this->parent;
	}

	public function __toString() {
		return (string)$this->parent() . '[' . var_export($this->index, true) . ']';
	}
}
