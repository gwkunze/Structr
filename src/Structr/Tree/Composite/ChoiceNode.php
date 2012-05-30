<?php

namespace Structr\Tree\Composite;

use Structr\Tree\Base\Node;

use Structr\Tree\Composite\ChoicePrototypeNode;
use Structr\Exception;

class ChoiceNode extends Node
{
    /**
     * @var Node[] The value of this node is expected to be one of these values
     */
    private $_alternatives = array();

    /**
     * Add an alternative
     * 
     * @param \Structr\Tree\RootNode $alternative A possible Structr definition
     *        for this node
     */
    public function addAlternative($alternative)
    {
        $this->_alternatives[] = $alternative;
    }

    /**
     * Add an alternative by defining it inline instead of supplying the full
     * definition
     * 
     * @return \Structr\Tree\Composite\ChoicePrototypeNode
     */
    public function altPrototype()
    {
        $prototype = new ChoicePrototypeNode($this);

        $this->_alternatives[] = $prototype;

        return $prototype;
    }

    /**
     * Remove all alternatives
     */
    public function clear()
    {
        $this->_alternatives = array();
    }

    /**
     * {@inheritdoc}
     * @throws Structr\Exception
     */
    public function _walk_value($value)
    {
        $value = parent::_walk_value($value);

        foreach ($this->_alternatives as $alternative) {
            try {
                return $alternative->_walk($value);
            } catch(Exception $e) {
            }
        }

        throw new Exception(sprintf(
            "No alternative matching type '%s'",
            gettype($value)
        ));
    }

}
