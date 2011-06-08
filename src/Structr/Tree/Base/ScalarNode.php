<?php

namespace Structr\Tree\Base;

abstract class ScalarNode extends Node {
	protected $coerce = false;
	/** @var bool Whether coercion should utilize strict rules (i.e. don't coerce strings to numbers if they have letters in them) */
	protected $coerce_strict = false;

	protected function coerceValue($value) {
		if($this->coerce === false) return $value;

		$type = gettype($value);

		if($type === $this->getScalarType()) return $value;

		if(is_callable(array($this, "coerceValueFrom" . $type))) {
			return $this->{"coerceValueFrom" . $type}($value, $this->coerce_strict);
		}

		if($this->coerce_strict || !@settype($value, $this->getScalarType()))
			throw new \Structr\Exceptions\CannotCoerceException("Can't coerce '$type' to '" . $this->getScalarType() ."'");

		return $value;
	}

	public abstract function getScalarType();

	public function coerce($strict = false) {
		$this->coerce = true;
		$this->coerce_strict = $strict;

		return $this;
	}

	public function value($parentValue = null) {
		$value = $parentValue;

		$value = $this->coerceValue($value);
		if(gettype($value) == $this->getScalarType()) {
			return $value;
		}

		throw new \Structr\Exceptions\InvalidTypeException("Invalid type for '" . gettype($value) . "', expecting " . $this->getScalarType());
	}

}
