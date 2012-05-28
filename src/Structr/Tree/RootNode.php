<?php

namespace Structr\Tree;

class RootNode extends Base\PrototypeNode
{
    /**
     * @var mixed Value to Structrize
     */
    private $_value;

    /**
     * Create a new root node
     * 
     * @param type $value Default value to check. Can be overriden by supplying
     *        a value to the run() method of \Sturctr\Tree\Node::run() if needed
     */
    public function __construct($value)
    {
        $this->_value = $value;
    }

    /**
     * Get the default value to check
     * 
     * @return type The default value to check, supplied at object create time
     */
    public function getValue()
    {
        return $this->_value;
    }
}
