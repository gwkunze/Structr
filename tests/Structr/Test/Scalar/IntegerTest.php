<?php
/**
 * Copyright (c) 2012 Gijs Kunze
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Structr\Test\Scalar;

use Structr\Structr;

class IntegerTest extends \PHPUnit_Framework_TestCase
{
    public function testCompare()
    {
        $value = 3;

        $expected = $value;

        $this->assertSame($expected, Structr::ize($value)
                                           ->isInteger()->gt(2)->end()
                                           ->run());
        $this->assertSame($expected, Structr::ize($value)
                                           ->isInteger()->gte(3)->end()
                                           ->run());

        $this->assertSame($expected, Structr::ize($value)
                                           ->isInteger()->lt(4)->end()
                                           ->run());
        $this->assertSame($expected, Structr::ize($value)
                                           ->isInteger()->lte(3)->end()
                                           ->run());
    }

    public function testClamp()
    {
        $array = array(
            "gte" => 5,
            "lte" => 5,
            "clamp" => array(
                0, 1, 2, 3, 4, 5, 6, 7, 8, 9
            )
        );

        $expected = array(
            "gte" => 9,
            "lte" => 2,
            "clamp" => array(
                3, 3, 3, 3, 4, 5, 6, 7, 7, 7
            )
        );

        $result = Structr::ize($array)
            ->isMap()
                ->strict()
                ->key("gte")
                    ->isInteger()->gte(9, true)->end()
                ->endKey()
                ->key("lte")
                    ->isInteger()->lte(2, true)->end()
                ->endKey()
                ->key("clamp")
                    ->isList()
                        ->item()
                            ->isInteger()->clamp(3, 7)->end()
                        ->endItem()
                    ->end()
                ->endKey()
            ->end()
            ->run();

        $this->assertSame($expected, $result);
    }
}
