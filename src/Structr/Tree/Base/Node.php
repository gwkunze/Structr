<?php

namespace Structr\Tree\Base;

abstract class Node
{
    private $_parent = null;
    private $_post = null;

    public function __construct($parent) {
        $this->setParent($parent);
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
