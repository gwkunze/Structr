<?php
/**
 * Copyright (c) 2012 Gijs Kunze
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Structr\Test\Composite;

use Structr\Structr;

class ListTest extends \PHPUnit_Framework_TestCase
{
    public function testSimpleList()
    {
        $array = array(1, 2, 3, 4, 5);

        $result = Structr::ize($array)
            ->isList()
            ->item()
                ->isInteger()->end()
            ->endItem()
            ->run();

        $this->assertSame($array, $result);
    }

    public function testCoercedList()
    {
        $array = array(2, "3", 3.12, true, 0);

        $result = Structr::ize($array)
            ->isList()
            ->item()
                ->isBoolean()->coerce()->end()
            ->endItem()
            ->run();

        $this->assertSame(array(true, true, true, true, false), $result);

        $result = Structr::ize($array)
            ->isList()
            ->item()
                ->isInteger()->coerce()->end()
            ->endItem()
            ->run();

        $this->assertSame(array(2, 3, 3, 1, 0), $result);

        $result = Structr::ize($array)
            ->isList()
            ->item()
                ->isString()->coerce()->end()
            ->endItem()
            ->run();

        $this->assertSame(array("2", "3", "3.12", "1", "0"), $result);
    }

    public function testNoPrototypeFail()
    {
        $this->setExpectedException('\\Structr\\Exception');

        Structr::ize(array())->isList()->run();
    }

    public function testInvalidTypeList()
    {
        $this->setExpectedException('\\Structr\\Exception');

        Structr::ize(1)
            ->isList()
            ->item()
                ->isInteger()->end()
            ->endItem()
            ->run();
    }

    public function testMapContentsInListDefinition()
    {
        $this->setExpectedException('\\Structr\\Exception');

        Structr::ize(array(1, "aa" => 2, 3))
            ->isList()
            ->item()
                ->isInteger()->end()
            ->endItem()
            ->run();
    }

    public function testMinimumSize()
    {
        $array = array(1, 2);

        $result = Structr::ize($array)
            ->isList()
            ->minSize(2)
            ->item()
                ->isInteger()->end()
            ->endItem()
            ->run();

        $this->assertSame($array, $result);
    }

    public function testMinimumSizeFail()
    {
        $this->setExpectedException('\\Structr\\Exception');

        $array = array(1, 2);

        Structr::ize($array)
            ->isList()
            ->minSize(3)
            ->item()
                ->isInteger()->end()
            ->endItem()
            ->run();
    }

    public function testMaximumSize()
    {
        $array = array(1, 2);

        $result = Structr::ize($array)
            ->isList()
            ->maxSize(2)
            ->item()
                ->isInteger()->end()
            ->endItem()
            ->run();

        $this->assertSame($array, $result);
    }

    public function testMaximumSizeFail()
    {
        $this->setExpectedException('\\Structr\\Exception');

        $array = array(1, 2);

        Structr::ize($array)
            ->isList()
            ->maxSize(1)
            ->item()
                ->isInteger()->end()
            ->endItem()
            ->run();
    }
}
