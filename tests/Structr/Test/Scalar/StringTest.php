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

    public function testMaxLength()
    {
        $string = 'foo';

        $result = Structr::ize($string)
            ->isString()->maxLength(4)->end()
            ->run();

        $this->assertSame($result, $string);
    }

    /**
     * @expectedException \Structr\Exception
     */
    public function testMaxLengthFail()
    {
        $string = 'foobar';

        Structr::ize($string)
            ->isString()->maxLength(4)->end()
            ->run();
    }

    public function testMinLength()
    {
        $string = 'foobar';

        $result = Structr::ize($string)
            ->isString()->minLength(4)->end()
            ->run();

        $this->assertSame($result, $string);
    }

    /**
     * @expectedException \Structr\Exception
     */
    public function testMinLengthFail()
    {
        $string = 'foo';

        Structr::ize($string)
            ->isString()->minLength(4)->end()
            ->run();
    }


    public function testLength()
    {
        $string = 'foobar';

        $result = Structr::ize($string)
            ->isString()->length(6)->end()
            ->run();

        $this->assertSame($result, $string);
    }

    /**
     * @expectedException \Structr\Exception
     */
    public function testLengthFailToShort()
    {
        $string = 'foo';

        Structr::ize($string)
            ->isString()->length(6)->end()
            ->run();
    }

    /**
     * @expectedException \Structr\Exception
     */
    public function testLengthFailToLong()
    {
        $string = 'foobarbaz';

        Structr::ize($string)
            ->isString()->length(6)->end()
            ->run();
    }
}
