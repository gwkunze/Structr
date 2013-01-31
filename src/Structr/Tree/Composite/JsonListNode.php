<?php
/**
 * Copyright (c) 2012 Gijs Kunze
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Structr\Tree\Composite;

use Structr\Structr;

class JsonListNode extends ListNode
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
