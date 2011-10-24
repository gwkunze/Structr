<?php

namespace Structr\Tree\Composite;

use Structr\Tree\Base\Node;

use Structr\Tree\Composite\ChoicePrototypeNode;
use Structr\Exception;

class ChoiceNode extends Node
{
    private $_alternatives = array();

    public function addAlternative($alternative) {
        $this->_alternatives[] = $alternative;
    }

    public function altPrototype() {
        $prototype = new ChoicePrototypeNode($this);

        $this->_alternatives[] = $prototype;

        return $prototype;
    }

    public function clear() {
        $this->_alternatives = array();
    }

    public function _walk_value($value) {
        $value = parent::_walk_value($value);

        foreach ($this->_alternatives as $alternative) {
            try {
                return $alternative->_walk_post($alternative
                                                ->_walk_value(
                                                    $alternative->_walk_pre($value)));
            } catch(Exception $e) {
            }
        }

        throw new Exception(
            "No alternative matching type '" . gettype($value) . "'");
    }

}
