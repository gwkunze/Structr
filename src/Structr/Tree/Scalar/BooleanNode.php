<?php

namespace Structr\Tree\Scalar;

use Structr\Tree\Base\ScalarNode;

class BooleanNode extends ScalarNode
{
    /**
     * {@inheritdoc}
     */
    public function getScalarType()
    {
        return 'boolean';
    }
}
