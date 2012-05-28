<?php

namespace Structr\Tree\Composite;

use Structr\Exception;

use Structr\Tree\Base\Node;

class ListNode extends Node
{
    /**
     * @var Structr\Tree\Composite\ListPrototypeNode The prototype for this list
     */
    private $_listPrototype = null;

    /**
     * @var int Minimum number of items expected in this node
     */
    private $_minimumLength = null;
    
    /**
     * @var int Maximum number of items expected in this node
     */
    private $_maximumLength = null;

    /**
     * Define an item within this list
     */
    public function item()
    {
        $this->_listPrototype = new ListPrototypeNode($this);

        return $this->_listPrototype;
    }

    /**
     * The list of this node is to have a minumum number of items
     * 
     * @param int $size The minimum number of items expected
     * @return \Structr\Tree\Composite\ListNode This node
     */
    public function minSize($size)
    {
        $this->_minimumLength = $size;

        return $this;
    }

    /**
     * The list of this node is to have a maximum number of items
     * 
     * @param int $size The maximum number of items expected
     * @return \Structr\Tree\Composite\ListNode
     */
    public function maxSize($size)
    {
        $this->_maximumLength = $size;

        return $this;
    }
    
    /**
     * {@inheritdoc}
     * @throws Structr\Exception
     */
    public function _walk_value($value = null)
    {
        $value = parent::_walk_value($value);

        if ($this->_listPrototype === null) {
            throw new Exception('List without item definitions');
        }

        if (!is_array($value)) {
            throw new Exception(sprintf(
                "Invalid type '%s', expecting 'list' (numerical array)",
                gettype($value)
            ));
        }

        $length = count($value);
        $this->checkLength($length, $value);

        $return = array();
        for ($i = 0; $i < $length; $i++) {
            if(!isset($value[$i])) {
                throw new Exception(sprintf(
                    "Invalid list, missing index '%d'. Might be a map.",
                    $i
                ));
            }
            $return[] = $this->_listPrototype->_walk($value[$i]);
        }
        return $return;
    }
    
    /**
     * Check if the length of the list is within bounds
     * 
     * @param int $length Length of the list
     * @throws Structr\Exception
     */
    protected function checkLength($length)
    {
        if ($this->_minimumLength !== null && $length < $this->_minimumLength) {
            throw new Exception(sprintf(
                "List smaller than minimum length (size='%d', min='%d')",
                $length,
                $this->_minimumLength
            ));
        }

        if ($this->_maximumLength !== null && $length > $this->_maximumLength) {
            throw new Exception(sprintf(
                "List larger then maximum length (size='%d', max='%d')",
                $length,
                $this->_maximumLength
            ));
        }
    }
}
