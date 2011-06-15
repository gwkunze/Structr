<?php

namespace Structr\Test;

use Structr\Structr;

class ScalarTest extends \PHPUnit_Framework_TestCase
{

    public function testNull() {
        $variable = null;

        $this->assertSame(Structr::ize($variable)->isNull()->run(), $variable);
    }

    public function testNullFail() {
        $this->setExpectedException('\\Structr\\Exception');

        $notNull = "foo";

        Structr::ize($notNull)->isNull()->run();
    }

    public function testInteger() {
        $variable = 3;

        $this->assertSame(Structr::ize($variable)
                                  ->isInteger()
                                  ->run(), $variable);
    }

    public function testIntegerFail() {
        $this->setExpectedException('\\Structr\\Exception');

        $notAnInteger = 3.1415926;

        Structr::ize($notAnInteger)->isInteger()->run();
    }

    public function testFloat() {
        $variable = 3.1415926;

        $this->assertSame(Structr::ize($variable)
                                  ->isFloat()
                                  ->run(), $variable);
    }

    public function testFloatFail() {
        $this->setExpectedException('\\Structr\\Exception');

        $notAFloat = true;

        Structr::ize($notAFloat)->isFloat()->run();
    }

    public function testBoolean() {
        $variable = true;

        $this->assertSame(Structr::ize($variable)
                                  ->isBoolean()
                                  ->run(), $variable);
    }

    public function testBooleanFail() {
        $this->setExpectedException('\\Structr\\Exception');

        $notABoolean = null;

        Structr::ize($notABoolean)->isBoolean()->run();
    }

    public function testString() {
        $variable = "The quick brown fox jumps over the lazy dog";

        $this->assertSame(Structr::ize($variable)
                                  ->isString()
                                  ->run(), $variable, "string == string");
    }

    public function testStringFail() {
        $this->setExpectedException('\\Structr\\Exception');

        $notAString = 3;

        Structr::ize($notAString)->isString()->run();
    }

    public function testStringRegexp() {
        $variable = "The quick brown fox jumps over the lazy dog";

        $this->assertSame($variable, Structr::ize($variable)
                                  ->isString()->regexp("/^The [\w\s]+$/")
                                  ->run(), "string (regexpmatch) == string");
    }

    public function testStringRegexpFail() {
        $this->setExpectedException('\\Structr\\Exception');

        $string = "12345AB";

        Structr::ize($string)->isString()->regexp("/^\d{4}\w{2}$/")->run();
    }
}