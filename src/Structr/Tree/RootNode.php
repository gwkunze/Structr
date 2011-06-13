<?php

namespace Structr\Tree;

class RootNode extends Base\PrototypeNode {
	/** @var mixed Value to Structrize */
	private $value;

	public function __construct($value) {
		$this->value = $value;
	}

	public function getValue() {
		return $this->value;
	}
}
