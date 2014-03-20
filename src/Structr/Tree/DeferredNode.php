<?php

namespace Structr\Tree;

use Structr\Exception;
use Structr\Tree\Base\Node;

/**
 * Node that stores a callable that will be
 * executed when the node is evaluated (Just In Time)
 */
class DeferredNode extends Node
{
    private $_callable;

    /**
     * Create a new node
     *
     * @param Callable $callable A callable that
     *        returns an instance of \Structr\Tree\Base\Node or \Structr\Tree\RootNode
     */
    public function __construct($callable)
    {
        if (!is_callable($callable))
        {
            throw new Exception('Argument to DeferredNode::__construct() must be callable');
        }
        $this->_callable = $callable;
    }

    public function _walk_value($value)
    {
        $value = parent::_walk_value($value);
        $node = call_user_func($this->_callable);
        if (!($node instanceof Node))
        {
            throw new Exception(
                'Callable supplied to is() must return an instance of \Structr\Tree\Base\Node'
            );
        }
        return $node->root()->_walk_value($value);
    }
}