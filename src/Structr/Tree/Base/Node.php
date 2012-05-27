<?php

namespace Structr\Tree\Base;

use \Structr\Exception;

/**
 * Basic node functionality for Structr
 */
abstract class Node
{
    /**
     * @var \Structr\Tree\Base\Node Parent of this node
     */
    private $_parent = null;
    
    /**
     * @var array functions to apply to the value of this node
     *     before checking the value of this node
     */
    private $_pre = array();
    
    /**
     * @var array functions to apply to the value of this node
     *     after checking the value of this node
     */
    private $_post = array();
    
    /**
     * @var string ID of this node
     */
    private $_id = null;

    /**
     * IDs that are currently registered on this node
     */
    private $_registeredIds = array();

    /**
     * Create a new node
     * 
     * @param \Structr\Tree\Base\Node Parent of this node
     */
    public function __construct($parent)
    {
        $this->setParent($parent);
    }

    /**
     * @return string The ID of this node
     */
    public function getId()
    {
        return $this->_id;
    }

    /**
     * Register an ID for this node to find it back later with Node::get()
     * 
     * @param string $id The id to store the node in
     * @param \Structr\Tree\Base\Node $value The Node to store for this ID
     * @throws Structr\Exception
     */
    protected function registerId($id, $value)
    {
        if ($this->_parent !== null) {
            return $this->root()->registerId($id, $value);
        }

        if (isset($this->_registeredIds[$id])) {
            throw new Exception("Duplicate id '$id'");
        }

        $this->_registeredIds[$id] = $value;
    }

    /**
     * Save this node in a Structr wide id for retieval later with Node::get
     * @param string $id The key to use to store this node
     */
    public function setId($id) {
        $this->registerId($id, $this);
        $this->_id = $id;
    }

    /**
     * Find a Node by a given ID
     * 
     * @param type $id The ID of the node to find
     * @param type $default Value to return if the ID is not registered
     * @return type mixed Either a \Structr\Tree\Base\Node or the value of $default
     */
    public function get($id, $default = null)
    {
        if ($this->_parent !== null) {
            return $this->root()->get($id);
        }

        if (!isset($this->_registeredIds[$id])) {
            return $default;
        }
        
        return $this->_registeredIds[$id];
    }

    /**
     * Get the parent of this node
     * 
     * @return \Structr\Tree\Base\Node the parent node of this node
     */
    public function parent()
    {
        return $this->_parent;
    }

    /**
     * Set the parent node for this node
     * 
     * @param \Structr\Tree\Base\Node $parent
     */
    public function setParent(Node $parent)
    {
        $this->_parent = $parent;
    }

    /**
     * Add a pre-processing callable to this node. 
     * Pre-processing callables are applied to the value of this node just
     * before the value is checked. 
     * The callables are applied in the order in which they are added.
     * @param callable $callable A valid PHP callable
     * @return \Structr\Tree\Base\Node This node
     */
    public function pre($callable)
    {
        if (is_callable($callable, false)) {
            $this->_pre[] = $callable;
        } else {
           throw new Exception('Invalid callable supplied to Node::pre()');
        }

        return $this;
    }

    /**
     * Add a post-processing callable to this node. 
     * Post-processing callables are applied to the value of this node just
     * after the value is checked. 
     * The callables are applied in the order in which they are added.
     * @param callable $callable A valid PHP callable
     * @return \Structr\Tree\Base\Node This node
     */
    public function post($callable)
    {
        if (is_callable($callable, false)) {
            $this->_post[] = $callable;
        } else {
           throw new Exception('Invalid callable supplied to Node::post()');
        }

        return $this;
    }

    /**
     * Return the root node of the current Structr tree
     * @return \Structr\Tree\RootNode The root node
     */
    public function root()
    {
        $return = $this;
        while (($parent = $return->parent()) != null) {
            $return = $parent;
        }
        return $return;
    }

    /**
     * End the current node and go back to the parent
     * @return type \Structr\Tree\Base\Node the parent of this node
     */
    public function end()
    {
        return $this->parent();
    }

    /**
     * Run the current Structr tree.
     * This will *ALWAYS* run the complete tree, running a Structr sub-tree is
     * not possible.
     * @param mixed $value Optional value to check. If not given the value of the
     *     RootNode instance this Structr is based on will be used
     * @return mixed The result of the Structr Tree applied to the value
     */
    public function run($value = null)
    {
        if ($this->_parent !== null) {
            return $this->root()->run($value);
        }

        if ($value === null) {
            $value = $this->getValue();
        }
        
        return $this->_walk($value);
    }

    /**
     * Shortcut function for _walk_pre, _walk_value, _walk_post.
     * These tree are often called in sequence, this method unclutters that a bit
     * @param mixed $value The value to walk
     * @return mixed The result of the walk
     */
    protected function _walk($value)
    {
        return
            $this->_walk_post($this->_walk_value($this->_walk_pre($value)));
    }
    
    /**
     * Apply all pre-processing callables to a value
     *
     * @param type $value The value to process
     * @return mixed Result of all pre-processing callables
     */
    protected function _walk_pre($value)
    {
        foreach ($this->_pre as $callable) {
            $value = call_user_func($callable, $value);
        }

        return $value;
    }
    
    /**
     * Process the current value
     * 
     * @param type $value The value to process
     * @return mixed Result of the processing
     */
    protected function _walk_value($value)
    {
        return $value;
    }

    /**
     * Apply all post-processing callables to a value
     *
     * @param type $value The value to process
     * @return mixed Result of all pre-processing callables
     */
    protected function _walk_post($value)
    {
        foreach ($this->_post as $callable) {
            $value = call_user_func($callable, $value);
        }

        return $value;
    }

    /**
     * Get the value of this node.
     * @return mixed Value of this node
     */
    protected function getValue()
    {
        return null;
    }
}
