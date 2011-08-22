<?php

namespace Structr\Test\Composite;

use Structr\Structr;

class MapTest extends \PHPUnit_Framework_TestCase
{

    public function testSimpleMap() {
        $array = array(
            "id" => "35",
            "baz" => "3.1416",
            "foo" => "bar"
        );

        $expected = array(
            "id" => 35,
            "baz" => 3.1416
        );

        $result = Structr::ize($array)
            ->isMap()
                ->key("id")
                    ->isInteger()->coerce()->end()
                ->endKey()
                ->key("baz")
                    ->isFloat()->coerce()->end()
                ->endKey()
            ->end()
            ->run();

        $this->assertSame($expected, $result);
    }

    public function testSimpleMapFail() {
        $this->setExpectedException("\\Structr\\Exception");

        $array = array(
            "id" => 135,
        );

        Structr::ize($array)
            ->isMap()
                ->key("id")
                    ->isInteger()->end()
                ->endKey()
                ->key("baz")
                    ->isFloat()->end()
                ->endKey()
            ->end()
            ->run();
    }

    public function testStrictMap() {
        $array = array(
            "id" => 12,
            "name" => "foo"
        );

        $expected = $array;

        $result = Structr::ize($array)
            ->isMap()
                ->strict()
                ->key("id")
                    ->isInteger()->end()
                ->endKey()
                ->key("name")
                    ->isString()->end()
                ->endKey()
            ->end()
            ->run();

        $this->assertSame($expected, $result);
    }

    public function testStrictMapFail() {
        $this->setExpectedException("\\Structr\\Exception");

        $array = array(
            "id" => 12,
            "name" => "foo",
            "superfluousExtraKey" => "bar"
        );

        Structr::ize($array)
            ->isMap()
                ->strict()
                ->key("id")
                    ->isInteger()->end()
                ->endKey()
                ->key("name")
                    ->isString()->end()
                ->endKey()
            ->end()
            ->run();
    }

    public function testMapDefault() {
        $array = array(
            "id" => 123,
            "foo" => "bar"
        );

        $expected = array(
            "id" => 123,
            "foo" => "bar",
            "baz" => "ban"
        );

        $result = Structr::ize($array)
            ->isMap()
                ->key("id")
                    ->isInteger()->end()
                ->endKey()
                ->key("foo")
                    ->isString()->end()
                ->endKey()
                ->key("baz")
                    ->defaultValue("ban")
                    ->isString()->end()
                ->endKey()
            ->end()
            ->run();

        $this->assertSame($expected, $result);
    }

    public function testMapKeyMatch() {
        $array = array(
            "name" => "John",
            "telephone-home" => "(555)0123",
            "telephone-work" => "(555)1234"
        );

        $expected = $array;

        $result = Structr::ize($array)
            ->isMap()
                ->strict()
                ->key("name")
                    ->isString()->end()
                ->endKey()
                ->keyMatch("/^telephone-/")
                    ->isString()->regexp("/^[\(\)0-9]+$/")->end()
                ->endKey()
            ->run();

        $this->assertSame($expected, $result);
    }

    public function testMapFunctionMatch() {
        $array = array(
            "a" => 0,
            "b" => 1,
            "c" => 2,
            "d" => 3,
            "e" => 4,
            "f" => 5,
            "g" => 6,
            "h" => 7,
            "i" => 8,
            "j" => 9,
        );

        $expected = array(
            "b" => 1,
            "c" => 2,
            "d" => 3,
            "f" => 5,
            "g" => 6,
            "h" => 7,
            "j" => 9,
        );

        $result = Structr::ize($array)
            ->isMap()
                ->keyMatch(function($key) { return !in_array($key, array('a', 'e', 'i', 'u', 'o')); })
                    ->isAny()->end()
                ->endKey()
            ->run();

        $this->assertSame($expected, $result);
    }
}
