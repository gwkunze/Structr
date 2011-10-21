<?php

namespace Structr\Tree\Scalar;

use Structr\Tree\Base\NumberNode;

class IntegerNode extends NumberNode
{

    public function getScalarType() {
        return "integer";
    }

    public function setType(&$value) {
        if ($this->_coerceStrict && is_string($value) && !ctype_digit($value))
            return false;
        return parent::setType($value);
    }
}
