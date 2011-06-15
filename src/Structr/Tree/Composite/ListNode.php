<?php

namespace Structr\Tree\Composite;

use Structr\Tree\Base\Node;

use Structr\Exception;

class ListNode extends Node
{
    /** @var ListPrototypeNode */
    private $_listPrototype = null;

    private $_minimumSize = null;
    private $_maximumSize = null;

    public function item() {
        $this->_listPrototype = new ListPrototypeNode($this);

        return $this->_listPrototype;
    }

    public function minSize($size) {
        $this->_minimumSize = $size;

        return $this;
    }

    public function maxSize($size) {
        $this->_maximumSize = $size;

        return $this;
    }

    public function _walk_value($value = null) {
        $value = parent::_walk_value($value);

        if ($this->_listPrototype === null) {
            throw new Exception("List without prototype");
        }

        if (!is_array($value)) {
            throw new Exception(
                "Invalid type '"
                . gettype($value) . "', expecting 'list' (numerical array)");
        }

        $return = array();

        $length = count($value);

        if ($this->_minimumSize !== null) {
            if($length < $this->_minimumSize)
                throw new Exception("List smaller than minimum size");
        }

        if ($this->_maximumSize !== null) {
            if($length > $this->_maximumSize)
                throw new Exception("List larger then maximum size");
        }

        for ($i = 0;$i < $length;$i++) {
            if(!isset($value[$i]))
                throw new Exception(
                    "Invalid list, missing index '{$i}'. Might be a map.");
            $return[] = $this->_listPrototype
                    ->_walk_post($this->_listPrototype
                                         ->_walk_value($value[$i]));
        }
        return $return;
    }
}
