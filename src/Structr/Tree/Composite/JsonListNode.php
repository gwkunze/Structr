<?php

namespace Structr\Tree\Composite;

use Structr\Structr;
use Structr\Exception;

class JsonListNode extends ListNode
{
    public function _walk_value($value = null)
    {
        $value = Structr::json_decode($value);
        return parent::_walk_value($value);
    }
}
