<?php

namespace Structr\Tree\Scalar;

use Structr\Tree\Base\ScalarNode;

class IntegerNode extends ScalarNode {

	public function getScalarType() {
		return "integer";
	}

}
