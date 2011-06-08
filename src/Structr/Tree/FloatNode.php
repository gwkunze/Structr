<?php

namespace Structr\Tree;

class FloatNode extends ScalarNode {

	public function __construct($parent) {
		$this->setParent($parent);
	}

	public function getScalarType() {
		return "double";
	}
}
