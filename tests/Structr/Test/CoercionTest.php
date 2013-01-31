<?php
/**
 * Copyright (c) 2012 Gijs Kunze
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Structr\Test;

use Structr\Structr;

class CoercionTest extends \PHPUnit_Framework_TestCase
{
    public function nullData()
    {
        return array(
          array(null),
          array(3),
          array(3.1415926),
          array(true),
          array("Foobar"),
          array(array()),
          array(new \stdClass),
        );
    }
    
    /**
     * @dataProvider nullData
     */
    public function testNull($var) {
        $value = Structr::ize($var)
                 ->isNull()->coerce()
                 ->run();
        
        $this->assertNull($value);
    }

    public function integerData()
    {
        return array(
          array(0, null),
          array(3, 3),
          array(3, 3.1415926),
          array(0, false),
          array(1, true),
          array(3, '3'),
          array(3, '3.1415'),
          array(3, '3.1415foo'),
          array(0, 'foo3.1415'),
          array(0, array()),
          array(1, new \stdClass()),
        );
    }
    
    /**
     * @dataProvider integerData
     */
    public function testInteger($expected, $input) {
        $value = Structr::ize($input)
                 ->isInteger()->coerce()
                 ->run();
        
        $this->assertSame($expected, $value);
    }

    public function testIntegerStrict() {
        $this->assertSame(123, Structr::ize("123")
                                   ->isInteger()->coerce(true)
                                   ->run());
        $this->assertSame(0, Structr::ize("0")
                                   ->isInteger()->coerce(true)
                                   ->run());

        $this->setExpectedException('\Structr\Exception');
        Structr::ize('invalidnumber')->isInteger()->coerce(true)->run();
    }

    public function floatData()
    {
        return array(
          array(0, null),
          array(3, 3),
          array(3.1415926, 3.1415926),
          array(0, false),
          array(1, true),
          array(3, '3'),
          array(3.1415, '3.1415'),
          array(3.1415, '3.1415foo'),
          array(0, 'foo3.1415'),
          array(0, array()),
          array(1, new \stdClass()),
        );
    }
    
    /**
     * @dataProvider floatData
     */
    public function testFloat($expected, $input) {
        $value = Structr::ize($input)
                 ->isFloat()->coerce()
                 ->run();
        
        $this->assertEquals($expected, $value, '', 0.0001);
    }

    public function booleanData()
    {
        return array(
          array(false, null),
          array(true, 3),
          array(true, 3.1415926),
          array(false, false),
          array(true, true),
          array(true, '3'),
          array(true, '3.1415'),
          array(true, '3.1415foo'),
          array(true, 'foo3.1415'),
          array(false, array()),
          array(true, new \stdClass()),
        );
    }
    
    /**
     * @dataProvider booleanData
     */
    public function testBoolean($expected, $input) {
        $value = Structr::ize($input)
                 ->isBoolean()->coerce()
                 ->run();
        
        $this->assertSame(
            $expected,
            $value,
            "coerce ".gettype($input)." -> bool"
        );
    }

    public function stringData()
    {
        return array(
          array('', null),
          array('3', 3),
          array('3.1415926', 3.1415926),
          array('', false),
          array('1', true),
          array('3f', '3f'),
          array('Array', array()),
          array('Object', new \stdClass()),
        );
    }
    
    /**
     * @dataProvider stringData
     */
    public function testString($expected, $input) {
        $value = Structr::ize($input)
                 ->isString()->coerce()
                 ->run();
        
        $this->assertSame(
            $expected,
            $value,
            "coerce (".gettype($input).") -> string"
        );
    }

}
