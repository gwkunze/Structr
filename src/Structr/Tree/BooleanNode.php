<?php

namespace Structr\Tree;

class BooleanNode extends ScalarNode {

	public function __construct($parent) {
		$this->setParent($parent);
	}

	public function getScalarType() {
		return "boolean";
	}
}
