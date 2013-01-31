<?php
/**
 * Copyright (c) 2012 Gijs Kunze
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Structr\Tree\Scalar;

use Structr\Tree\Base\ScalarNode;

class NullNode extends ScalarNode
{
    /**
     * {@inheritdoc}
     */
    public function getScalarType()
    {
        return 'NULL';
    }
}
