<?php

namespace Structr\Test;

use Structr\Structr;

class PostTest extends \PHPUnit_Framework_TestCase
{

    public function testSimplePostProcessing() {
        $value = 3;

        $expected = 4;

        $result = Structr::ize($value)
            ->isInteger()
                ->post(function($v) {
                    return $v + 1;
                })
            ->end()
            ->run();

        $this->assertSame($expected, $result);
    }

    public function testMap() {
        $array = array(
            "id" => 123,
            "items" => array(1,2,3),
            "foo" => "bar"
        );

        $expected = array(
            "id" => "ID-00000123",
            "items" => array(9, 4, 1),
            "foo" => "barbarbar",
            "ban" => "baz"
        );

        $result = Structr::ize($array)
            ->isMap()
                ->strict()
                ->key("id")
                    ->isInteger()->end()
                    ->post(function($v) {
                        return sprintf("ID-%08d", $v);
                    })
                ->endKey()
                ->key("items")
                    ->isList()
                        ->item()
                            ->isInteger()
                            ->post(function($v) {
                                return $v * $v;
                            })
                            ->end()
                        ->endItem()
                    ->end()
                    ->post(function($v) {
                        return array_reverse($v);
                    })
                ->endKey()
                ->key("foo")
                    ->isString()->end()
                    ->post(function($v) {
                        return str_repeat($v, 3);
                    })
                ->endKey()
                ->post(function($v) {
                    $v["ban"] = "baz"; return $v;
                })
            ->end()
            ->run();

        $this->assertSame($expected, $result);
    }
}
