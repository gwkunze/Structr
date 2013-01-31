<?php
/**
 * Copyright (c) 2012 Gijs Kunze
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Structr\Test\Scalar;

use Structr\Structr;

class StringTest extends \PHPUnit_Framework_TestCase
{

    public function testRegexp() {
        $string = "1991-08-06";

        $expected = $string;

        $result = Structr::ize($string)
            ->isString()
                ->regexp("/^\d{4}-\d{2}-\d{2}$/")
            ->end()
            ->run();

        $this->assertSame($expected, $result);
    }

    public function testRegexpFail() {
        $this->setExpectedException("\\Structr\\Exception");

        $string = "1991/08/06";

        Structr::ize($string)
            ->isString()
                ->regexp("/^\d{4}-\d{2}-\d{2}$/")
            ->end()
            ->run();
    }

    public function testEnum() {
        $string = "foo";

        $enum = array("foo", "bar", "baz");

        $expected = $string;

        $result = Structr::ize($string)
            ->isString()
                ->enum($enum)
            ->end()
            ->run();

        $this->assertSame($expected, $result);
    }

    public function testEnumFail() {
        $this->setExpectedException("\\Structr\\Exception");
        $string = "foobar";

        $enum = array("foo", "bar", "baz");

        $expected = $string;

        Structr::ize($string)
            ->isString()
                ->enum($enum)
            ->end()
            ->run();
    }
}