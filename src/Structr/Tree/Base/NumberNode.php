<?php

namespace Structr\Tree\Base;

use Structr\Tree\Base\ScalarNode;

use Structr\Exception;

abstract class NumberNode extends ScalarNode
{

    private $_hasGt = false;
    private $_hasGte = false;
    private $_hasLt = false;
    private $_hasLte = false;
    private $_compareGt;
    private $_compareGte;
    private $_compareLt;
    private $_compareLte;
    private $_clampGte = false;
    private $_clampLte = false;

    public function gt($value) {
        $this->_hasGt = true;
        $this->_compareGt = $value;

        return $this;
    }

    public function gte($value, $clamp = false) {
        $this->_hasGte = true;
        $this->_compareGte = $value;
        $this->_clampGte = $clamp;

        return $this;
    }

    public function lt($value) {
        $this->_hasLt = true;
        $this->_compareLt = $value;

        return $this;
    }

    public function lte($value, $clamp = false) {
        $this->_hasLte = true;
        $this->_compareLte = $value;
        $this->_clampLte = $clamp;

        return $this;
    }

    public function clamp($low, $high) {
        $this->gte($low, true);
        $this->lte($high, true);

        return $this;
    }

    public function _walk_value($value) {
        $value = parent::_walk_value($value);

        if ($this->_hasGte) {
            if($value < $this->_compareGte && !$this->_clampGte)
                throw new Exception(
                    "Value '{$value}' lower than"
                    . " allowed ({$this->_compareGte})");
            $value = max($this->_compareGte, $value);
        }
        if ($this->_hasLte) {
            if($value > $this->_compareLte && !$this->_clampLte)
                throw new Exception(
                    "Value '{$value}' higher"
                    . " than allowed ({$this->_compareLte})");
            $value = min($this->_compareLte, $value);
        }

        if ($this->_hasGt && $value <= $this->_compareGt)
            throw new Exception(
                "Value '{$value}' lower than allowed ({$this->_compareGt})");
        if ($this->_hasLt && $value >= $this->_compareLt)
            throw new Exception(
                "Value '{$value}' higher than allowed ({$this->_compareLt})");

        return $value;
    }
}
