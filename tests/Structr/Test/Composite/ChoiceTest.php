<?php
/**
 * Copyright (c) 2012 Gijs Kunze
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Structr\Test\Composite;

use Structr\Structr;

class ChoiceTest extends \PHPUnit_Framework_TestCase
{
    public function testSimpleData()
    {
        return array(
            array(3, 3, true),
            array(6, 6, true),
            array('3.1415', 3.1415, true),
            array('a', '', false),
            array(array(1,2,3), '', false),
            array(new \stdClass(), '', false)
        );
    }
    
    /**
     * @dataProvider testSimpleData
     */
    public function testSimple($input, $expected, $success)
    {
        if (!$success) {
            $this->setExpectedException('\Structr\Exception');
        }
        
        $result = Structr::ize($input)
            ->isChoice()
                ->altPrototype()
                    ->isInteger()->end()
                ->endPrototype()
                ->altPrototype()
                    ->isFloat()->coerce(true)->end()
                ->endPrototype()
            ->end()
            ->run();

        $this->assertSame($expected, $result);
    }
}
