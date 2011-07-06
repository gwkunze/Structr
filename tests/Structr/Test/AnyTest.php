<?php

namespace Structr\Test;

use Structr\Structr;

class AnyTest extends \PHPUnit_Framework_TestCase
{
    public function testScalar() {
        $value = 34;
        $this->assertSame($value, Structr::ize($value)->isAny()->run());
    }

    public function testNull() {
        $value = NULL;
        $this->assertSame($value, Structr::ize($value)->isAny()->run());
    }

    public function testComplex() {
        $value = array(
            1,
            34,
            "foo",
            array(),
            array(1,2,3,4)
        );
        
        $this->assertSame($value, Structr::ize($value)->isAny()->run());
    }
}
