<?php

namespace Structr\Tree\Base;

use Structr\Exception;

abstract class ScalarNode extends Node
{
    protected $_coerce = false;
    /** @var bool Whether coercion should utilize strict rules (i.e. don't
     * coerce strings to numbers if they have letters in them) */
    protected $_coerceStrict = false;

    protected function coerceValue($value) {
        if ($this->_coerce === false) return $value;

        $type = gettype($value);

        if ($type === $this->getScalarType()) return $value;

        if (is_callable(array($this, "coerceValueFrom" . $type))) {
            return $this->{"coerceValueFrom" . $type}($value,
                                                      $this->_coerceStrict);
        }

        if ($this->_coerceStrict || !@settype($value, $this->getScalarType()))
            throw new Exception("Can't coerce '$type' to '"
                                . $this->getScalarType() ."'");

        return $value;
    }

    public abstract function getScalarType();

    public function coerce($strict = false) {
        $this->_coerce = true;
        $this->_coerceStrict = $strict;

        return $this;
    }

    protected function _walk_value($value) {
        $value = parent::_walk_value($value);

        $value = $this->coerceValue($value);
        if (gettype($value) == $this->getScalarType()) {
            return $value;
        }

        throw new Exception("Invalid type for '" . gettype($value)
                            . "', expecting " . $this->getScalarType());
    }
}
