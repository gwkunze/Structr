<?php

namespace Structr\Test;

use Structr\Structr;

use Structr\Exceptions\InvalidTypeException;

class ScalarTest extends \PHPUnit_Framework_TestCase {

	public function testNull() {
		$variable = null;

		$this->assertSame(Structr::ize($variable)->isNull()->run(), $variable);
	}

	public function testNullFail() {
		$this->setExpectedException('\\Structr\\Exceptions\\InvalidTypeException');

		$not_null = "foo";

		Structr::ize($not_null)->isNull()->run();
	}

	public function testInteger() {
		$variable = 3;

		$this->assertSame(Structr::ize($variable)->isInteger()->run(), $variable);
	}

	public function testIntegerFail() {
		$this->setExpectedException('\\Structr\\Exceptions\\InvalidTypeException');

		$not_an_integer = 3.1415926;

		Structr::ize($not_an_integer)->isInteger()->run();
	}

	public function testFloat() {
		$variable = 3.1415926;

		$this->assertSame(Structr::ize($variable)->isFloat()->run(), $variable);
	}

	public function testFloatFail() {
		$this->setExpectedException('\\Structr\\Exceptions\\InvalidTypeException');

		$not_a_float = true;

		Structr::ize($not_a_float)->isFloat()->run();
	}

	public function testBoolean() {
		$variable = true;

		$this->assertSame(Structr::ize($variable)->isBoolean()->run(), $variable);
	}

	public function testBooleanFail() {
		$this->setExpectedException('\\Structr\\Exceptions\\InvalidTypeException');

		$not_a_boolean = null;

		Structr::ize($not_a_boolean)->isBoolean()->run();
	}

	public function testString() {
		$variable = "The quick brown fox jumps over the lazy dog";

		$this->assertSame(Structr::ize($variable)->isString()->run(), $variable, "string == string");

		$this->assertSame(Structr::ize($variable)->isString()->regexp("/^The [\w\s]+$/")->run(), $variable, "string (regexpmatch) == string");
	}

	public function testStringFail() {
		$this->setExpectedException('\\Structr\\Exceptions\\InvalidTypeException');

		$not_a_string = 3;

		Structr::ize($not_a_string)->isString()->run();
	}

	public function testStringRegexpFail() {
		$this->setExpectedException('\\Structr\\Exceptions\\NoRegexpMatchException');

		$not_a_string = "12345AB";

		Structr::ize($not_a_string)->isString()->regexp("/^\d{4}\w{2}$/")->run();
	}
}