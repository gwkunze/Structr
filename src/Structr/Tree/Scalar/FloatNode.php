<?php

namespace Structr\Tree\Scalar;

use Structr\Tree\Base\NumberNode;

class FloatNode extends NumberNode
{

    public function getScalarType() {
        return "double";
    }
}
