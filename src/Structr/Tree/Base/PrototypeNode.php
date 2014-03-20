<?php
/**
 * Copyright (c) 2012 Gijs Kunze
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Structr\Tree\Base;

use Structr\Structr;
use Structr\Tree\RootNode;

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

abstract class PrototypeNode extends Node
{
    /**
     * @var \Structr\Tree\Base\Node Child node declaring type
     */
    private $_prototype = null;

    /**
     * Get the Prototype of this Node, i.e., the concrete
     * implementation of a Node (e.g. IntegerNode) this PrototypeNode is wrapping
     * 
     * @return \Structr\Tree\Base\Node Child node declaring type
     */
    protected function getPrototype()
    {
        return $this->_prototype;
    }

    /**
     * The value of this node is expected to be an integer
     * 
     * @return \Structr\Tree\Scalar\IntegerNode
     */
    public function isInteger()
    {
        $this->_prototype = new IntegerNode($this);

        return $this->_prototype;
    }

    /**
     * The value of this node is expected to be a float
     * 
     * @return \Structr\Tree\Scalar\FloatNode
     */
    public function isFloat()
    {
        $this->_prototype = new FloatNode($this);

        return $this->_prototype;
    }

    /**
     * The value of this node is expected to be a boolean
     * 
     * @return \Structr\Tree\Scalar\BooleanNode
     */
    public function isBoolean()
    {
        $this->_prototype = new BooleanNode($this);

        return $this->_prototype;
    }

    /**
     * The value of this node is expected to be a string
     * 
     * @return \Structr\Tree\Scalar\StringNode
     */
    public function isString()
    {
        $this->_prototype = new StringNode($this);

        return $this->_prototype;
    }

    /**
     * The value of this node is expected to be null
     * 
     * @return \Structr\Tree\Scalar\NullNode
     */
    public function isNull()
    {
        $this->_prototype = new NullNode($this);

        return $this->_prototype;
    }

    /**
     * The value of this node is expected to be a \DateTime
     * 
     * @return \Structr\Tree\Scalar\DateTimeNode
     */
    public function isDateTime()
    {
        $this->_prototype = new DateTimeNode($this);

        return $this->_prototype;
    }

    /**
     * The value of this node is expected to be a float
     * 
     * @return \Structr\Tree\Scalar\AnyNode
     */
    public function isAny()
    {
        $this->_prototype = new AnyNode($this);

        return $this->_prototype;
    }

    /**
     * The value of this node is expected to be a list.
     * A list is an array with default numeric keys.
     * 
     * @return \Structr\Tree\Composite\ListNode
     */
    public function isList()
    {
        $this->_prototype = new ListNode($this);

        return $this->_prototype;
    }
    
    /**
     * The value of this node is expected to be a map.
     * A map is an associative array.
     * 
     * @return \Structr\Tree\Composite\MapNode
     */
    public function isMap()
    {
        $this->_prototype = new MapNode($this);

        return $this->_prototype;
    }
    
    /**
     * The value of this node is expected to be a JSON encoded list
     * A list is an array with default numeric keys.
     * 
     * @return \Structr\Tree\Composite\JsonMapNode
     */
    public function isJsonMap()
    {
        $this->_prototype = new JsonMapNode($this);

        return $this->_prototype;
    }
    
    /**
     * The value of this node is expected to be a JSON encoded map
     * A map is an associative array.
     * 
     * @return \Structr\Tree\Composite\JsonListNode
     */
    public function isJsonList()
    {
        $this->_prototype = new JsonListNode($this);

        return $this->_prototype;
    }

    /**
     * The value of this node is expected to be one of limited options
     * 
     * @return \Structr\Tree\Composite\ChoiceNode
     */
    public function isChoice()
    {
        $this->_prototype = new ChoiceNode($this);

        return $this->_prototype;
    }

    /**
     * The value of this is node is expected to be described by a Structr
     * definition defined earlier.
     * 
     * @param mixed $definition Either the name of a Structr definition defined
     *        earlier, or a RootNode object representing a Structr tree
     * @return \Structr\Tree\RootNode
     */
    public function is($definition)
    {
        if (is_object($definition) && $definition instanceof Node) {
            $this->_prototype = $definition;
        } else {
            $this->_prototype = clone Structr::getDefinition($definition);
        }

        $this->_prototype->setParent($this);

        return $this->_prototype;
    }

    /**
     * The value of this is node is expected to be described by one of a
     * limited list of earlier defined Structr definitions
     * 
     * @param string $searchString Pattern of definitions to look for, i.e., 
     *        app\model\*, data\*, etc
     * @return \Structr\Tree\Composite\ChoiceNode
     */
    public function isOneOf($searchString)
    {
        $this->_prototype = new ChoiceNode($this);
        
        $definitions = Structr::getDefinitions($searchString);
        foreach ($definitions as $definition) {
            $alt = clone $definition;
            $alt->setParent($this->_prototype);
            $this->_prototype->addAlternative($alt);
        }

        return $this->_prototype;
    }

    /**
     * {@inheritdoc}
     */
    public function _walk_value($value)
    {
        $value = parent::_walk_value($value);
        return $this->getPrototype()->_walk_value($value);
    }

    /**
     * {@inheritdoc}
     */
    public function _walk_pre($value)
    {
        $value = parent::_walk_pre($value);
        return $this->getPrototype()->_walk_pre($value);
    }

    /**
     * {@inheritdoc}
     */
    public function _walk_post($value)
    {
        $value = parent::_walk_post($value);
        return $this->getPrototype()->_walk_post($value);
    }
}
