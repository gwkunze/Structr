<?php

namespace Structr\Test;

use Structr\Structr;

class DefinitionTest extends \PHPUnit_Framework_TestCase
{

    public function testSimpleDefinition() {
        Structr::clearAll();

        Structr::define("test")->isInteger()->coerce()->end();

        $value = "3";
        $expected = 3;

        $result = Structr::ize($value)->is("test")->run();
        $this->assertSame($expected, $result);
    }

    public function testMultiDefinition() {
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
                ->listPrototype()
                    ->isOneOf("data/*")->end()
                ->endPrototype()
            ->end()
            ->run();

        $this->assertSame($expected, $result);
    }

}
