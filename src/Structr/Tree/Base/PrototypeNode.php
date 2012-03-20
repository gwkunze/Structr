<?php

namespace Structr\Tree\Base;

use Structr\Structr;

use Structr\Tree\Scalar\IntegerNode;
use Structr\Tree\Scalar\FloatNode;
use Structr\Tree\Scalar\BooleanNode;
use Structr\Tree\Scalar\NullNode;
use Structr\Tree\Scalar\StringNode;
use Structr\Tree\Scalar\DateTimeNode;
use Structr\Tree\Scalar\AnyNode;

use Structr\Tree\Composite\ListNode;
use Structr\Tree\Composite\MapNode;
use Structr\Tree\Composite\JsonListNode;
use Structr\Tree\Composite\JsonMapNode;
use Structr\Tree\Composite\ChoiceNode;

use Structr\Tree\DefinitionNode;

abstract class PrototypeNode extends Node
{
    /** @var \Structr\Tree\Base\Node Child node declaring type */
    private $_prototype = null;

    /**
     * @return \Structr\Tree\Base\Node
     */
    protected function getPrototype() {
        return $this->_prototype;
    }

    /**
     * @return \Structr\Tree\Scalar\IntegerNode
     */
    public function isInteger() {
        $this->_prototype = new IntegerNode($this);

        return $this->_prototype;
    }

    /**
     * @return \Structr\Tree\Scalar\FloatNode
     */
    public function isFloat() {
        $this->_prototype = new FloatNode($this);

        return $this->_prototype;
    }

    /**
     * @return \Structr\Tree\Scalar\BooleanNode
     */
    public function isBoolean() {
        $this->_prototype = new BooleanNode($this);

        return $this->_prototype;
    }

    /**
     * @return \Structr\Tree\Scalar\StringNode
     */
    public function isString() {
        $this->_prototype = new StringNode($this);

        return $this->_prototype;
    }

    /**
     * @return \Structr\Tree\Scalar\NullNode
     */
    public function isNull() {
        $this->_prototype = new NullNode($this);

        return $this->_prototype;
    }

    /**
     * @return \Structr\Tree\Scalar\DateTime
     */
    public function isDateTime() {
        $this->_prototype = new DateTimeNode($this);

        return $this->_prototype;
    }

    /**
     * @return \Structr\Tree\Scalar\AnyNode
     */
    public function isAny() {
        $this->_prototype = new AnyNode($this);

        return $this->_prototype;
    }

    /**
     * @return \Structr\Tree\Composite\ListNode
     */
    public function isList() {
        $this->_prototype = new ListNode($this);

        return $this->_prototype;
    }
    
    /**
     * @return \Structr\Tree\Composite\MapNode
     */
    public function isMap() {
        $this->_prototype = new MapNode($this);

        return $this->_prototype;
    }
    
    /**
     * @return \Structr\Tree\Composite\JsonMapNode
     */
    public function isJsonMap() {
        $this->_prototype = new JsonMapNode($this);

        return $this->_prototype;
    }
    
    /**
     * @return \Structr\Tree\Composite\JsonListNode
     */
    public function isJsonList() {
        $this->_prototype = new JsonListNode($this);

        return $this->_prototype;
    }

    /**
     * @return \Structr\Tree\Composite\ChoiceNode
     */
    public function isChoice() {
        $this->_prototype = new ChoiceNode($this);

        return $this->_prototype;
    }

    /**
     * @param $definition string
     * @return \Structr\Tree\DefinitionNode
     */
    public function is($definition) {
        if (is_object($definition)) {
            $this->_prototype = $definition;
        } else {
            $this->_prototype = clone Structr::getDefinition($definition);
        }

        $this->_prototype->setParent($this);

        return $this->_prototype;
    }

    public function isOneOf($searchString) {
        $definitions = Structr::getDefinitions($searchString);

        /** @var $prototype \Structr\Tree\Composite\ChoiceNode */
        $this->_prototype = new ChoiceNode($this);

        foreach ($definitions as $definition) {
            $alt = clone $definition;
            $alt->setParent($this->_prototype);
            $this->_prototype->addAlternative($alt);
        }

        return $this->_prototype;
    }

    public function end() {
        return $this->parent();
    }

    public function _walk_value($value) {
        $value = parent::_walk_value($value);
        return $this->getPrototype()->_walk_value($value);
    }

    public function _walk_pre($value) {
        $value = parent::_walk_pre($value);
        return $this->getPrototype()->_walk_pre($value);
    }

    public function _walk_post($value) {
        $value = parent::_walk_post($value);
        return $this->getPrototype()->_walk_post($value);
    }
}
