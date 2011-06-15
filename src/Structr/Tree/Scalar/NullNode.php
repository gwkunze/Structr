<?php

namespace Structr\Tree\Scalar;

use Structr\Tree\Base\ScalarNode;

class NullNode extends ScalarNode
{

    public function getScalarType() {
        return "NULL";
    }
}
