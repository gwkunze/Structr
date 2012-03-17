<?php

namespace Structr\Tree\Composite;

use Structr\Tree\Base\PrototypeNode;

use Structr\Exception;

class MapKeyNode extends PrototypeNode
{
    private $_required = true;
    private $_optional = false;
    private $_defaultValue = null;

    private $_name;

    public function setName($name) {
        $this->_name = $name;
    }

    public function getName() {
        return $this->_name;
    }

    public function defaultValue($value) {
        $this->_required = false;
        $this->_defaultValue = $value;

        return $this;
    }

    public function optional($optional = true) {
        $this->_optional = $optional;

        return $this;
    }

    public function isOptional() {
        return $this->_optional;
    }

    public function endKey() {
        return $this->parent();
    }

    public function _walk_value_unset() {
        if ($this->_required) {
            throw new Exception("Missing key '{$this->_name}'");
        }

        return $this->_defaultValue;
    }
}
