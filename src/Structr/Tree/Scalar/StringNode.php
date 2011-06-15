<?php

namespace Structr\Tree\Scalar;

use Structr\Tree\Base\ScalarNode;

use Structr\Exception;

class StringNode extends ScalarNode
{
    private $_regexp = null;
    private $_enum = null;
    private $_enumCaseInsensitive = true;

    public function getScalarType() {
        return "string";
    }

    public function regexp($regexp) {
        $this->_regexp = $regexp;

        return $this;
    }

    public function enum(array $enum, $caseInsensitive = true) {
        $this->_enum = $enum;
        $this->_enumCaseInsensitive = $caseInsensitive;

        return $this;
    }

    protected function coerceValueFromObject($value, $strict) {
        if (is_callable(array($value, "__toString"))) {
            return (string)$value;
        }

        if ($strict) {
            throw new Exception(
                "Cannot coerce an object to a string in strict mode");
        }

        return "Object";
    }

    public function _walk_value($value = null) {
        $value = parent::_walk_value($value);

        if ($this->_regexp !== null && !preg_match($this->_regexp, $value)) {
            throw new Exception("String did not match regular expression");
        }

        if ($this->_enum !== null) {
            $regexp = "/^((" . implode(")|(",
                                       array_map(function($item) {
                                           return preg_quote($item, "/");
                                       }, $this->_enum))
                      . "))$/" . (($this->_enumCaseInsensitive)?"i":"");
            if(!preg_match($regexp, $value))
                throw new Exception("'{$value}' not part of enum");
        }

        return $value;
    }
}
