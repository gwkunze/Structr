<?php
/**
 * Copyright (c) 2012 Gijs Kunze
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Structr\Test;

use Structr\Structr;

class PreTest extends \PHPUnit_Framework_TestCase
{
    public function testSimplePreProcessing()
    {
        $value = 1;

        $expected = 2;

        $result = Structr::ize($value)
            ->pre(function ($x) {
                return $x + 1;
            })
            ->isInteger()
            ->end()
            ->run();

        $this->assertSame($expected, $result);
    }

    public function testObjectToArray()
    {
        $value = new \stdClass;
        $value->foo = 'bar';

        $expected = array('foo' => 'bar');
        
        $result = Structr::ize($value)
            ->pre(function ($o) {
                return get_object_vars($o);
            })
            ->isMap()
            ->key('foo')->isString()->end()->endKey()
            ->run();
        $this->assertSame($expected, $result);
    }
}
