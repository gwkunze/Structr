<?php

namespace Structr\Tree\Composite;

use Structr\Tree\Base\Node;

use Structr\Exception;

class MapKeyNode extends Node
{

    /** @var \Structr\Tree\Composite\MapKeyPrototypeNode */
    private $_keyPrototype = null;

    private $_required = true;
    private $_defaultValue = null;

    private $_name;

    public function setName($name) {
        $this->_name = $name;
    }

    public function getName() {
        return $this->_name;
    }

    /**
     * @return \Structr\Tree\Composite\MapKeyPrototypeNode
     */
    public function valuePrototype() {
        $this->_keyPrototype = new MapKeyPrototypeNode($this);

        return $this->_keyPrototype;
    }

    public function defaultValue($value) {
        $this->_required = false;
        $this->_defaultValue = $value;

        return $this;
    }

    public function endKey() {
        return $this->parent();
    }

    public function _walk_value($value) {
        $value = parent::_walk_value($value);
        return $this->_keyPrototype->_walk_post($this->_keyPrototype
                                                        ->_walk_value($value));
    }

    public function _walk_value_unset() {
        if ($this->_required) {
            throw new Exception("Missing key '{$this->_name}'");
        }

        return $this->_defaultValue;
    }
}
