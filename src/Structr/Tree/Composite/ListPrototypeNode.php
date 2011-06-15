<?php

namespace Structr\Tree\Composite;

use Structr\Tree\Base\PrototypeNode;

class ListPrototypeNode extends PrototypeNode
{
    public function endItem() {
        return $this->parent();
    }
}
