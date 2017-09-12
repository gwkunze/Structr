<?php
/**
 * Copyright (c) 2012 Gijs Kunze
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Structr\Tree\Composite;

use Structr\Tree\Base\PrototypeNode;

use Structr\Exception;

class MapKeyNode extends PrototypeNode
{
    /**
     * @var boolean Whether this key is required
     */
    private $_required = true;
    
    /**
     * @var boolean Whether this key is optional
     */
    private $_optional = false;
    
    /**
     * @var mixed Default value to use when no value is supplied
     */
    private $_defaultValue = null;

    /**
     * @var string The name of this key
     */
    private $_name;

    /**
     * Set the name of this key
     *
     * @param string $name The name
     */
    public function setName($name)
    {
        $this->_name = $name;
    }

    /**
     * Get the name of this key
     *
     * @return string The name
     */
    public function getName()
    {
        return $this->_name;
    }

    /**
     * Overrides description to set the concrete node's description
     *
     * @param string $description
     * @return MapKeyNode
     */
    public function description($description)
    {
        $this->_prototype->setDescription($description);
        return $this;
    }

    /**
     * Overrides getDescription to return the concrete node's description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->_prototype->getDescription();
    }

    /**
     * Overrides setDescription to set the concrete node's description
     *
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->_prototype->setDescription($description);
    }

    /**
     * Set the default value for this key. When no value is set and this node
     * hasn't been set explicitly to optional, this default value will be
     * returned.
     *
     * @param mixed $value The default value
     * @return \Structr\Tree\Composite\MapKeyNode This node
     */
    public function defaultValue($value)
    {
        $this->_required = false;
        $this->_defaultValue = $value;

        return $this;
    }

    /**
     * This key is optional; If the key is not provided no exception will be
     * thrown.
     *
     * @param boolean $optional
     * @return \Structr\Tree\Composite\MapKeyNode This node
     */
    public function optional($optional = true)
    {
        $this->_optional = $optional;

        return $this;
    }

    /**
     * Whether this key is optional
     *
     * @return boolean
     */
    public function isOptional()
    {
        return $this->_optional;
    }

    /**
     * Jump back to the parent
     *
     * @return \Structr\Tree\Base\Node the parent node
     */
    public function endKey()
    {
        return $this->parent();
    }

    /**
     * Walk the value. Throw an exception if the value is required. If it's not
     * required, set it to the default value.
     *
     * @return string The default value, in case this node is not required
     * @throws Structr\Exception
     */
    public function _walk_value_unset()
    {
        if ($this->_required) {
            throw new Exception(sprintf(
                "Missing key '%s'",
                $this->_name
            ));
        }

        return $this->_defaultValue;
    }
}
