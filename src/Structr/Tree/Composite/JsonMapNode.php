<?php

namespace Structr\Tree\Composite;

use Structr\Structr;

class JsonMapNode extends MapNode
{
    /**
     * {@inheritdoc}
     */
    public function _walk_value($value = null)
    {
        $value = Structr::json_decode($value);
        return parent::_walk_value($value);
    }
}
