<?php

namespace Structr\Tree\Composite;

use Structr\Tree\Base\Node;

use Structr\Exception;

class MapNode extends Node
{
    private $_keys = array();
    private $_regexp_keys = array();
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

    public function keyMatch($regexp) {
        $this->_regexp_keys[$regexp] = new MapKeyNode($this);
        $this->_regexp_keys[$regexp]->setName($regexp);

        return $this->_regexp_keys[$regexp];
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

        if ($this->_strict && count($value)) {
            throw new Exception(
                "Unexpected key(s) " . implode(', ', array_keys($value)));
        }
        return $return;
    }

}
