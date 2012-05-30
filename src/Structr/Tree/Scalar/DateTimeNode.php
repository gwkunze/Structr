<?php

namespace Structr\Tree\Scalar;

use Structr\Exception;

use Structr\Tree\Base\Node;

class DateTimeNode extends Node
{
    /**
     * {@inheritdoc}
     * throws Structr\Exception
     */
    protected function _walk_value($value)
    {
        $value = parent::_walk_value($value);

        if (!empty($value) && !($value instanceof \DateTime))
        {
            try {
                $value = new \DateTime($value);
            } catch (\Exception $e) {
                throw new Exception(sprintf(
                    "Failed to parse \\DateTime from '%s'",
                    $value
                ));
            }
        }
        
        return $value;
    }
}
