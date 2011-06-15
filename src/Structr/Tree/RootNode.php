<?php

namespace Structr\Tree;

class RootNode extends Base\PrototypeNode
{
    /** @var mixed Value to Structrize */
    private $_value;

    public function __construct($value) {
        $this->_value = $value;
    }

    public function getValue() {
        return $this->_value;
    }
}
