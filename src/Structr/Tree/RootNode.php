<?php

namespace Structr\Tree;

class RootNode extends Base\PrototypeNode {
	/** @var mixed Value to Structrize */
	private $value;

	public function __construct($value) {
		$this->value = $value;
	}

	public function value($parentValue = null) {
		if($this->getPrototype() == null) {
			return $this->value;
		}

		return $this->getPrototype()->value($this->value);
	}

}
