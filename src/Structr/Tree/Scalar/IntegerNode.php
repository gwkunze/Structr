<?php

namespace Structr\Tree\Scalar;

use Structr\Tree\Base\NumberNode;

class IntegerNode extends NumberNode {

	public function getScalarType() {
		return "integer";
	}

}
