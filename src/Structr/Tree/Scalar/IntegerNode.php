<?php
/**
 * Copyright (c) 2012 Gijs Kunze
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Structr\Tree\Scalar;

use Structr\Tree\Base\NumberNode;

class IntegerNode extends NumberNode
{
    /**
     * {@inheritdoc}
     */
    public function getScalarType()
    {
        return 'integer';
    }

    /**
     * {@inheritdoc}
     */
    public function setType(&$value)
    {
        if ($this->_coerceStrict && is_string($value) && !ctype_digit($value)) {
            return false;
        }
        return parent::setType($value);
    }
}
