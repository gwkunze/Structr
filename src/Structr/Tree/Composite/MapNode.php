<?php

namespace Structr\Tree\Composite;

use Structr\Tree\Base\Node;

use Structr\Exception;

class MapNode extends Node
{
    private $_keys = array();
    private $_regexp_keys = array();
    private $_function_keys = array();
    private $_strict = false;

    /**
     * @param string $keyname
     * @return \Structr\Tree\Composite\MapKeyNode
     *
     */
    public function key($keyname) {
        $this->_keys[$keyname] = new MapKeyNode($this);
        $this->_keys[$keyname]->setName($keyname);

        return $this->_keys[$keyname];
    }

    public function keyMatch($matcher, $name = null) {
        if(is_callable($matcher, false, $callable_name)) {
            if($name === null) $name = $callable_name;
            $node = new MapKeyNode($this);
            $node->setName($name);
            $this->_function_keys[] = array("node" => $node, "function" => $matcher);
            return $node;
        } else {
            if($name === null) $name = $matcher;
            $node = new MapKeyNode($this);
            $node->setName($name);
            $this->_regexp_keys[$matcher] = $node;

            return $node;
        }
    }

    public function strict() {
        $this->_strict = true;
        return $this;
    }

    public function _walk_value($value) {
        $value = parent::_walk_value($value);

        if (!is_array($value)) {
            throw new Exception(
                "Invalid type '" . gettype($value)
                . "', expecting 'map' (associative array)");
        }

        $return = array();

        foreach ($this->_keys as $key => $val) {
            if (isset($value[$key])) {
                $return[$key] = $val->_walk_post($val
                                                 ->_walk_value($value[$key]));
            } else {
                $return[$key] = $val->_walk_post($val
                                                 ->_walk_value_unset());
            }

            unset($value[$key]);
        }

        foreach ($this->_regexp_keys as $regexp => $val) {
            foreach(array_keys($value) as $arrayKey) {
                if(preg_match($regexp, $arrayKey)) {
                    $return[$arrayKey] = $val->_walk_post($val->_walk_value($value[$arrayKey]));

                    unset($value[$arrayKey]);
                }
            }
        }

        foreach ($this->_function_keys as $function) {
            foreach(array_keys($value) as $arrayKey) {
                if($function["function"]($arrayKey)) {
                    $return[$arrayKey] = $function['node']->_walk_post($function['node']->_walk_value($value[$arrayKey]));

                    unset($value[$arrayKey]);
                }
            }
        }

        if ($this->_strict && count($value)) {
            throw new Exception(
                "Unexpected key(s) " . implode(', ', array_keys($value)));
        }
        return $return;
    }

}
