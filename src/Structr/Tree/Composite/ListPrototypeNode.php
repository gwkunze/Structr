<?php
/**
 * Copyright (c) 2012 Gijs Kunze
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Structr\Tree\Composite;

use Structr\Tree\Base\PrototypeNode;

class ListPrototypeNode extends PrototypeNode
{
    /**
     * Jump back to the parent node of this node
     *
     * @return \Structr\Tree\Base\Node The parent node of this node
     */
    public function endItem()
    {
        return $this->parent();
    }
}
