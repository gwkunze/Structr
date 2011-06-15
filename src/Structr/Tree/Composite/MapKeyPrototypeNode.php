<?php

namespace Structr\Tree\Composite;

use Structr\Tree\Base\PrototypeNode;

class MapKeyPrototypeNode extends PrototypeNode
{

    public function endPrototype() {
        return $this->parent();
    }
}
