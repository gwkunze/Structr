<?php
/**
 * Copyright (c) 2012 Gijs Kunze
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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
     * @param mixed $value Default value to check. Can be overriden by supplying
     *        a value to the run() method of \Sturctr\Tree\Node::run() if needed
     */
    public function __construct($value)
    {
        $this->_value = $value;
    }

    /**
     * Get the default value to check
     *
     * @return mixed The default value to check, supplied at object create time
     */
    public function getValue()
    {
        return $this->_value;
    }
}
