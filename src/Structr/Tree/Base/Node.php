<?php

namespace Structr\Tree\Base;

use \Structr\Exception;

abstract class Node
{
    private $_parent = null;
    private $_post = null;
    private $_id = null;

    private $_registeredIds = array();

    public function __construct($parent) {
        $this->setParent($parent);
    }

    public function getId() {
        return $this->_id;
    }

    protected function registerId($id, $value) {
        if ($this->_parent !== null) {
            return $this->root()->registerId($id, $value);
        }

        if (isset($this->_registeredIds[$id])) {
            throw new Exception("Duplicate id '$id'");
        }

        $this->_registeredIds[$id] = $value;
    }

    public function setId($id) {
        $this->registerId($id, $this);
        $this->_id = $id;
    }

    public function get($id, $default = null) {
        if ($this->_parent !== null) {
            return $this->root()->get($id);
        }

        if (!isset($this->_registeredIds[$id])) {
            return $default;
        }
        
        return $this->_registeredIds[$id];
    }

    public function parent() {
        return $this->_parent;
    }

    public function setParent(Node $parent) {
        $this->_parent = $parent;
    }

    public function post($function) {
        $this->_post = $function;

        return $this;
    }

    public function root() {
        $return = $this;
        while (($parent = $return->parent()) != null) {
            $return = $parent;
        }
        return $return;
    }

    public function end() {
        return $this->parent();
    }

    public function run() {
        if ($this->_parent !== null) {
            return $this->root()->run();
        }

        $value = $this->getValue();

        $return = $this->_walk_value($value);

        return $this->_walk_post($return);
    }

    protected function _walk_value($value) {
        return $value;
    }

    protected function _walk_post($value) {

        if ($this->_post !== null) {
            $value = call_user_func($this->_post, $value);
        }

        return $value;
    }

    protected function getValue() {
        return null;
    }
}
