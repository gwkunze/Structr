<?php
/**
 * Copyright (c) 2012 Gijs Kunze
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Structr\Test;

use Structr\Structr;

class DefinitionTest extends \PHPUnit_Framework_TestCase
{
    public function testSimpleDefinition()
    {
        Structr::clearAll();

        Structr::define("test")->isInteger()->coerce()->end();

        $value = "3";
        $expected = 3;

        $result = Structr::ize($value)->is("test")->run();
        $this->assertSame($expected, $result);
    }

    public function testMultiDefinition()
    {
        Structr::clearAll();

        Structr::define("data/integer")->isInteger();
        Structr::define("data/string")->isString();
        Structr::define("data/float")->isFloat();

        $list = array(
            3,
            "foo",
            123.123
        );
        $expected = $list;

        $result = Structr::ize($list)
            ->isList()
                ->item()
                    ->isOneOf("data/*")->end()
                ->endItem()
            ->end()
            ->run();

        $this->assertSame($expected, $result);
    }

    public function testDefinitionClosure()
    {
        Structr::clearAll();

        Structr::define("foo")->isInteger()->post(function ($v) {
            return $v * 2;
        });

        $value = 3;

        $expected = $value * 2;

        $result = Structr::ize($value)->is("foo")->run();

        $this->assertSame($expected, $result);
    }

    public function testEmptyDefinition()
    {
        Structr::clearAll();

        $structr = Structr::define()->isInteger()->end();
        $this->assertInstanceOf('Structr\Tree\RootNode', $structr);
    }


    public function testRunWithValue()
    {
        Structr::clearAll();
        $structr = Structr::define()->isInteger()->end();

        $expected = 2;
        $this->assertSame($expected, $structr->run(2));
    }

    public function testWithDefinition()
    {
        Structr::clearAll();
        $structr = Structr::define()->is(
            Structr::define()->isInteger()->coerce()
        );

        $this->assertSame(4, $structr->run(4));
    }
}
