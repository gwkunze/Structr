<?php
/**
 * Copyright (c) 2012 Gijs Kunze
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Structr\Tree\Base;

use Structr\Exception;

/**
 * Base functionality for all scalar nodes
 */
abstract class ScalarNode extends Node
{
    /**
     * @var boolean Whether to coerce the value of this node, i.e., cast it
     *      to the required type if it not already of this type.
     *      For example, isInteger() for value "4" (string) would change
     *      it to 4 (int)
     */
    protected $_coerce = false;
    
    /**
     * @var bool Whether coercion should utilize strict rules (i.e. don't
     *      coerce strings to numbers if they have letters in them)
     */
    protected $_coerceStrict = false;

    /**
     * Coerce the value of this node to match the scalar type of this node
     *
     * @param mixed $value The value to be coerced
     * @return mixed Coercion result
     * @throws Structr\Exception
     */
    protected function coerceValue($value)
    {
        if ($this->_coerce === false) {
            return $value;
        }

        $type = gettype($value);

        if ($type === $this->getScalarType()) {
            return $value;
        }
        
        $method = 'coerceValueFrom'.$type;
        if (is_callable(array($this, $method))) {
            return $this->$method($value, $this->_coerceStrict);
        }

        $typeok = $this->setType($value);
        if ($this->_coerceStrict && !$typeok) {
            throw new Exception(sprintf(
                "Can't coerce '%s' to '%s'",
                $type,
                $this->getScalarType()
            ));
        }

        return $value;
    }

    /**
     * Get the scalar type for this node (i.e., integer, string, etc)
     */
    abstract public function getScalarType();

    /**
     * The value of this node must be strictly coerced
     * Same as calling coerce with parameter true
     *
     * @return \Structr\Tree\Base\ScalarNode This node
     */
    public function strict()
    {
        return $this->coerce(true);
    }
    
    /**
     * The value of this node must be coerced
     *
     * @param bool $strict Whether to use string coercion (i.e. don't
     *        coerce strings to numbers if they have letters in them)
     * @return \Structr\Tree\Base\ScalarNode This node
     */
    public function coerce($strict = false)
    {
        $this->_coerce = true;
        $this->_coerceStrict = $strict;

        return $this;
    }

    /**
     * Coerce a value to the scalar type of this node
     *
     * @param mixed $value The value to coerce
     * @return bool Whether setting the value to thew new type was successful
     */
    public function setType(&$value)
    {
        return @settype($value, $this->getScalarType());
    }

    /**
     * {@inheritdoc}
     */
    protected function _walk_value($value)
    {
        $value = parent::_walk_value(
            $this->coerceValue($value)
        );

        if (gettype($value) == $this->getScalarType()) {
            return $value;
        }

        throw new Exception(sprintf(
            "Invalid type for '%s', expecting '%s'",
            gettype($value),
            $this->getScalarType()
        ));
    }
}
