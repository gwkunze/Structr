<?php
/**
 * Copyright (c) 2012 Gijs Kunze
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Structr\Test\Scalar;

use Structr\Structr;

class FloatTest extends \PHPUnit_Framework_TestCase
{

    public function testCompare() {
        $value = 2.14;

        $expected = $value;

        $this->assertSame($expected, Structr::ize($value)
                                           ->isFloat()->gt(2.1)->end()
                                           ->run());
        $this->assertSame($expected, Structr::ize($value)
                                           ->isFloat()->gte(2.14)->end()
                                           ->run());

        $this->assertSame($expected, Structr::ize($value)
                                           ->isFloat()->lt(4)->end()
                                           ->run());
        $this->assertSame($expected, Structr::ize($value)
                                           ->isFloat()->lte(2.14)->end()
                                           ->run());
    }

    public function testClamp() {
        $array = array(
            "gte" => 5.3,
            "lte" => 5.3,
            "clamp" => array(
                0.1, 1.2, 2.3, 3.4, 4.5, 5.6, 6.7, 7.8, 8.9, 9.0
            )
        );

        $expected = array(
            "gte" => 9.11,
            "lte" => 2.12,
            "clamp" => array(
                3.14, 3.14, 3.14, 3.4, 4.5, 5.6, 6.7, 7.5, 7.5, 7.5
            )
        );

        $result = Structr::ize($array)
            ->isMap()
                ->strict()
                ->key("gte")
                    ->isFloat()->gte(9.11, true)->end()
                ->endKey()
                ->key("lte")
                    ->isFloat()->lte(2.12, true)->end()
                ->endKey()
                ->key("clamp")
                    ->isList()
                        ->item()
                            ->isFloat()->clamp(3.14, 7.5)->end()
                        ->endItem()
                    ->end()
                ->endKey()
            ->end()
            ->run();

        $this->assertSame($expected, $result);
    }
}
