<?php
/**
 * Copyright (c) 2012 Gijs Kunze
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Structr\Test;

use Structr\Structr;

class AnyTest extends \PHPUnit_Framework_TestCase
{
    public function testScalar()
    {
        $value = 34;
        $this->assertSame($value, Structr::ize($value)->isAny()->run());
    }

    public function testNull()
    {
        $value = null;
        $this->assertSame($value, Structr::ize($value)->isAny()->run());
    }

    public function testComplex()
    {
        $value = array(
            1,
            34,
            "foo",
            array(),
            array(1,2,3,4)
        );
        
        $this->assertSame($value, Structr::ize($value)->isAny()->run());
    }
}
