<?php

namespace Structr\Test\Composite;

use Structr\Structr;

class ChoiceTest extends \PHPUnit_Framework_TestCase
{

    public function testSimpleMap() {
        $array = array(
            3,
            6,
            "3.1415"
        );

        $expected = array(
            3,
            6,
            3.1415
        );

        $result = Structr::ize($array)
            ->isList()
                ->item()
                    ->isChoice()
                        ->altPrototype()
                            ->isInteger()->end()
                        ->endPrototype()
                        ->altPrototype()
                            ->isFloat()->coerce()->end()
                        ->endPrototype()
                    ->end()
                ->endItem()
            ->end()
            ->run();

        $this->assertSame($expected, $result);
    }

}
