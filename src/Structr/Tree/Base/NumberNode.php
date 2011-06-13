<?php

namespace Structr\Tree\Base;

use Structr\Tree\Base\ScalarNode;

use Structr\Exception;

abstract class NumberNode extends ScalarNode {

	private $has_gt = false;
	private $has_gte = false;
	private $has_lt = false;
	private $has_lte = false;
	private $compare_gt;
	private $compare_gte;
	private $compare_lt;
	private $compare_lte;
	private $clamp_gte = false;
	private $clamp_lte = false;

	public function gt($value) {
		$this->has_gt = true;
		$this->compare_gt = $value;

		return $this;
	}

	public function gte($value, $clamp = false) {
		$this->has_gte = true;
		$this->compare_gte = $value;
		$this->clamp_gte = $clamp;

		return $this;
	}

	public function lt($value) {
		$this->has_lt = true;
		$this->compare_lt = $value;

		return $this;
	}

	public function lte($value, $clamp = false) {
		$this->has_lte = true;
		$this->compare_lte = $value;
		$this->clamp_lte = $clamp;

		return $this;
	}

	public function clamp($low, $high) {
		$this->gte($low, true);
		$this->lte($high, true);

		return $this;
	}

	public function value($parentValue = null) {
		$value = parent::value($parentValue);

		if($this->has_gte) {
			if($value < $this->compare_gte && !$this->clamp_gte)
				throw new Exception("Value '{$value}' lower than allowed ({$this->compare_gte})");
			$value = max($this->compare_gte, $value);
		}
		if($this->has_lte) {
			if($value > $this->compare_lte && !$this->clamp_lte)
				throw new Exception("Value '{$value}' higher than allowed ({$this->compare_lte})");
			$value = min($this->compare_lte, $value);
		}

		if($this->has_gt && $value <= $this->compare_gt)
			throw new Exception("Value '{$value}' lower than allowed ({$this->compare_gt})");
		if($this->has_lt && $value >= $this->compare_lt)
			throw new Exception("Value '{$value}' higher than allowed ({$this->compare_lt})");


		return $value;
	}
}
