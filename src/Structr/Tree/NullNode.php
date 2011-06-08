<?php

namespace Structr\Tree;

class NullNode extends ScalarNode {

	public function __construct($parent) {
		$this->setParent($parent);
	}

	public function getScalarType() {
		return "NULL";
	}
}
