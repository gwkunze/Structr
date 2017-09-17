<?php
/**
 * Copyright (c) 2012 Gijs Kunze
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Structr\Tree\Scalar;

use Structr\Exception;

use Structr\Tree\Base\ScalarNode;

class StringNode extends ScalarNode
{
    /**
     * @var string The regular expression the value of this node is expected
     *      to satify (if any)
     */
    private $_regexp = null;
    
    /**
     * @var array The list of enum options to check for (if any)
     */
    private $_enum = null;

    /**
     * @var mixed The string length of this node is expected to be longer
     * than this value
     */
    private $_minLength = false;

    /**
     * @var mixed The string length of this node is expected to be shorter
     * than this value
     */
    private $_maxLength = false;
    
    /**
     * @var bool Whether to do case sensitive enum checking
     */
    private $_enumCaseInsensitive = true;

    /**
     * {@inheritdoc}
     */
    public function getScalarType()
    {
        return 'string';
    }

    /**
     * The value of this node is expected to satisfy a regular expression
     * 
     * @param string $regexp The regular expression the value must satisfy
     * @return \Structr\Tree\Scalar\StringNode This node
     */
    public function regexp($regexp)
    {
        $this->_regexp = $regexp;

        return $this;
    }

    /**
     * The value of this node is expected to be part of a finite list of options
     * 
     * @param array $enum The list of options the value must be part of
     * @param boolean $caseInsensitive Whether checking must be done in a
     *        case sensitive manner
     * @return \Structr\Tree\Scalar\StringNode This node
     */
    public function enum(array $enum, $caseInsensitive = true)
    {
        $this->_enum = $enum;
        $this->_enumCaseInsensitive = $caseInsensitive;

        return $this;
    }

    /**
     * The length of the string value of this node is expected to be equal to
     * a fixed value
     *
     * @param number $value
     * @return \Structr\Tree\Scalar\StringNode This node
     */
    public function length($value)
    {
        $this->_minLength = $value;
        $this->_maxLength = $value;

        return $this;
    }

    /**
     * The length of the string value of this node is expected to be greater
     * than a fixed value
     *
     * @param number $value
     * @return \Structr\Tree\Scalar\StringNode This node
     */
    public function minLength($value)
    {
        $this->_minLength = $value;

        return $this;
    }

    /**
     * The length of the string value of this node is expected to be less than
     * a fixed value
     *
     * @param number $value
     * @return \Structr\Tree\Scalar\StringNode This node
     */
    public function maxLength($value)
    {
        $this->_maxLength = $value;

        return $this;
    }

    /**
     * Coerce an object to a string
     * 
     * @param object $value The object to coerce to a string
     * @param bool $strict Whether objects without a __toString object are
     *        allowed
     * @return string Description of the object
     * @throws Exception
     */
    protected function coerceValueFromObject($value, $strict)
    {
        if (is_callable(array($value, '__toString'))) {
            return (string) $value;
        }

        if ($strict) {
            throw new Exception(
                'Cannot coerce an object to a string in strict mode'
            );
        }

        return 'Object';
    }

    /**
     * {@inheritdoc}
     */
    public function _walk_value($value = null)
    {
        $value = parent::_walk_value($value);

        $this->checkRegexp($value);
        $this->checkEnum($value);
        $this->checkMinLength($value);
        $this->checkMaxLength($value);

        return $value;
    }
    
    /**
     * Checks if the value satisfies the regular expression for this node
     * (if any)
     * 
     * @param string $value The value to check
     * @throws Structr\Exception
     */
    protected function checkRegexp($value)
    {
        if ($this->_regexp !== null && !preg_match($this->_regexp, $value)) {
            throw new Exception('String did not match regular expression');
        }
    }
    
    /**
     * Check if the value if part of the enum for this node (if any)
     * 
     * @param string $value The value to check
     * @throws Structr\Exception
     */
    protected function checkEnum($value)
    {
        if ($this->_enum !== null) {
            if ($this->_enumCaseInsensitive) {
                $value = strtolower($value);
                $enum = array_map('strtolower', $this->_enum);
            } else {
                $enum = $this->_enum;
            }
            
            if (!in_array($value, $enum)) {
                throw new Exception("'{$value}' not part of enum");
            }
        }
    }

    /**
     * Check that the string length is within bounds (longer than)
     *
     * @param number $value The value to check
     * @throws Structr\Exception
     */
    protected function checkMinLength($value)
    {
        if ($this->_minLength && strlen($value) < $this->_minLength) {
            $this->tooShort($value, $this->_minLength);
        }
    }

    /**
     * Check that the string length is within bounds (shorter than)
     *
     * @param number $value The value to check
     * @throws Structr\Exception
     */
    protected function checkMaxLength($value)
    {
        if ($this->_maxLength && strlen($value) > $this->_maxLength) {
            $this->tooLong($value, $this->_maxLength);
        }
    }

    /**
     * Error: the string is too short
     *
     * @throws Structr\Exception
     */
    protected function tooShort($value, $min)
    {
        throw new Exception(sprintf(
            "Length of string '%s' (%d) is shorter than allowed (%d)",
            $value,
            strlen($value),
            $min
        ));
    }

    /**
     * Error: the string is too long
     *
     * @throws Structr\Exception
     */
    protected function tooLong($value, $min)
    {
        throw new Exception(sprintf(
            "Length of string '%s' (%d) is longer than allowed (%d)",
            $value,
            strlen($value),
            $min
        ));
    }
}
