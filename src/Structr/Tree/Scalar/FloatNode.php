<?php

namespace Structr\Tree\Scalar;

use Structr\Tree\Base\ScalarNode;

class FloatNode extends ScalarNode {

	public function getScalarType() {
		return "double";
	}
}
