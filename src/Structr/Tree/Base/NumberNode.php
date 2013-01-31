<?php
/**
 * Copyright (c) 2012 Gijs Kunze
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Structr\Tree\Base;

use Structr\Tree\Base\ScalarNode;

use Structr\Exception;

/**
 * Base functionality for all scalar nodes that
 * are concerned with numbers
 */
abstract class NumberNode extends ScalarNode
{
    /**
     * @var mixed The value of this node is expected to be greater than
     *      this value
     */
    private $_gt = false;
    
    /**
     * @var mixed The value of this node is expected to be greater than or equal
     *      to this value
     */
    private $_gte = false;
    
    /**
     * @var mixed The value of this node is expected to be less than this value
     */
    private $_lt = false;
    
    /**
     * @var mixed The value of this node is expected to be less than or equal to
     *      this value
     */
    private $_lte = false;
    
    /**
     * @var bool Whether to clamp the value if it is greater than the value of
     *      _gte
     */
    private $_clampGte = false;
    
    /**
     * @var bool Whether to clamp the value if it is less than the value of
     *      _lte
     */
    private $_clampLte = false;

    /**
     * The value of this node is expected to be greater than a fixed value
     * 
     * @param number $value
     * @return \Structr\Tree\Base\NumberNode
     */
    public function gt($value)
    {
        $this->_gt = $value;

        return $this;
    }

    /**
     * The value of this node is expected to be greater than or equal to
     * a fixed value
     *
     * @param number $value
     * @param bool $clamp
     * @return \Structr\Tree\Base\NumberNode
     */
    public function gte($value, $clamp = false)
    {
        $this->_gte = $value;
        $this->_clampGte = $clamp;

        return $this;
    }

    /**
     * The value of this node is expected to be less than a fixed value
     * 
     * @param number $value
     * @return \Structr\Tree\Base\NumberNode
     */
    public function lt($value)
    {
        $this->_lt = $value;

        return $this;
    }

    /**
     * The value of this node is expected to be less than or equal to a
     * fixed value
     * 
     * @param number $value
     * @param bool $clamp
     * @return \Structr\Tree\Base\NumberNode
     */
    public function lte($value, $clamp = false)
    {
        $this->_lte = $value;
        $this->_clampLte = $clamp;

        return $this;
    }

    /**
     * The value of this node is expected to be between two fixed values.
     * If it isn't, it will be forced within the interval.
     *
     * @param number $low
     * @param number $high
     * @throws \Structr\Exception
     * @return \Structr\Tree\Base\NumberNode
     */
    public function clamp($low, $high)
    {
        if ($high <= $low) {
            throw new Exception(
                'Low value must be stricly lower than high value for clamping'
            );
        }
        $this->gte($low, true);
        $this->lte($high, true);

        return $this;
    }
    
    /**
     * {@inheritdoc}
     */
    public function setType(&$value)
    {
        if ($this->_coerceStrict && !is_numeric($value)) {
            return false;
        }
        
        return parent::setType($value);
    }

    /**
     * {@inheritdoc}
     * @throws Structr\Exception
     */
    public function _walk_value($value)
    {
        $value = parent::_walk_value($value);

        $value = $this->checkGte($value);
        $value = $this->checkLte($value);
        $this->checkGt($value);
        $this->checkLt($value);

        return $value;
    }
    
    /**
     * Check that the value is within bounds (greater than or equal)
     * 
     * @param number $value The value to check
     * @return number
     * @throws Structr\Exception
     */
    protected function checkGte($value)
    {
        if ($this->_gte) {
            if ($value < $this->_gte && !$this->_clampGte) {
                $this->tooLow($value, $this->_gte);
            }
            $value = max($this->_gte, $value);
        }
        
        return $value;
    }
    
    /**
     * Check that the value is within bounds (less than or equal)
     * 
     * @param number $value The value to check
     * @return number
     * @throws Structr\Exception
     */
    protected function checkLte($value)
    {
        if ($this->_lte) {
            if ($value > $this->_lte && !$this->_clampLte) {
               $this->tooHigh($value, $this->_lte);
            }
            $value = min($this->_lte, $value);
        }
        
        return $value;
    }
    
    /**
     * Check that the value is within bounds (greater than)
     * 
     * @param number $value The value to check
     * @throws Structr\Exception
     */
    protected function checkGt($value)
    {
        if ($this->_gt && $value <= $this->_gt) {
            $this->tooLow($value, $this->_gt);
        }
    }
    
    /**
     * Check that the value is within bounds (less than)
     * 
     * @param number $value The value to check
     * @throws Structr\Exception
     */
    protected function checkLt($value)
    {
        if ($this->_lt && $value >= $this->_lt) {
            $this->tooHigh($value, $this->_lt);
        }
    }
    
    /**
     * Error: the value is too high
     * 
     * @throws Structr\Exception
     */
    protected function tooHigh($value, $max)
    {
         throw new Exception(sprintf(
            "Value '%s' is higher than allowed (%f)",
            $value,
            $max
        ));
    }
    
    /**
     * Error: the value is too low
     * 
     * @throws Structr\Exception
     */
    protected function tooLow($value, $min)
    {
        throw new Exception(sprintf(
            "Value '%s' is lower than allowed (%f)",
            $value,
            $min
        ));
    }
}
