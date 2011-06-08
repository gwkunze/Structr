<?php

namespace Structr\Tree;

class IntegerNode extends ScalarNode {

	public function __construct($parent) {
		$this->setParent($parent);
	}

	public function getScalarType() {
		return "integer";
	}

}
