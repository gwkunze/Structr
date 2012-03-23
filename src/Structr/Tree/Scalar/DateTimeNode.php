<?php

namespace Structr\Tree\Scalar;

use Structr\Tree\Base\Node;

use Structr\Exception;

class DateTimeNode extends Node
{

    protected function _walk_value($value) {
        $value = parent::_walk_value($value);

        if (!($value instanceof \DateTime) && !empty($value))
        {
            try
            {
                $value = new \DateTime($value);
            }
            catch (\Exception $e)
            {
                throw new Exception("Failed to parse DateTime from '$value'");
            }
        }
        return $value;
    }

}
