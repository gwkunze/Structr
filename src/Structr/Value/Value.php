<?php

namespace Structr\Value;

class Value {
	private $value;
	private $name = null;
	private $defaultSet = false;
	private $default = null;

	/**
	 * @param mixed $value
	 */
	public function __construct($value, $name = null) {
		$this->value = $value;
		$this->name = $name;
	}

	public function value() {
		$values = $this->values();

		if(count($values) > 0)
			return $values[0];

		throw new \Structr\Exception\NoValueException("No value for " . (string)$this);
	}

	public function defaultValue($value) {
		return $this->join(new Value($value));
	}

	public function values() {
		return array($this->value);
	}

	public function index($index) {
		return new IndexValue($this, $index);
	}

	public function parent() {
		return null;
	}

	public function join(Value $value) {
		return new JoinValue($this, $value);
	}

	public function __toString() {
		$name = $this->name;
		if($name === null) $name = gettype($this->value());
		return '<<' . $name . '>>';
	}
}

