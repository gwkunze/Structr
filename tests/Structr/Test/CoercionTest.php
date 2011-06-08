<?php

namespace Structr\Test;

use Structr\Structr;

use Structr\Exceptions\InvalidTypeException;

class CoercionTest extends \PHPUnit_Framework_TestCase {

	public function testNull() {
		$this->assertNull(Structr::ize(null)->isNull()->coerce()->run());
		$this->assertNull(Structr::ize(3)->isNull()->coerce()->run());
		$this->assertNull(Structr::ize(3.1415926)->isNull()->coerce()->run());
		$this->assertNull(Structr::ize(true)->isNull()->coerce()->run());
		$this->assertNull(Structr::ize("Foobar")->isNull()->coerce()->run());
		$this->assertNull(Structr::ize(array())->isNull()->coerce()->run());
		$this->assertNull(Structr::ize(new \stdClass())->isNull()->coerce()->run());
	}

	public function testInteger() {
		$this->assertSame(0, Structr::ize(null)->isInteger()->coerce()->run());
		$this->assertSame(3, Structr::ize(3)->isInteger()->coerce()->run());
		$this->assertSame(3, Structr::ize(3.1415926)->isInteger()->coerce()->run());
		$this->assertSame(0, Structr::ize(false)->isInteger()->coerce()->run());
		$this->assertSame(1, Structr::ize(true)->isInteger()->coerce()->run());
		$this->assertSame(3, Structr::ize("3")->isInteger()->coerce()->run());
		$this->assertSame(3, Structr::ize("3.145")->isInteger()->coerce()->run());
		$this->assertSame(3, Structr::ize("3.145foo")->isInteger()->coerce()->run());
		$this->assertSame(0, Structr::ize("foo1.145")->isInteger()->coerce()->run());
		$this->assertSame(0, Structr::ize(array())->isInteger()->coerce()->run());
		$this->assertSame(1, Structr::ize(new \stdClass())->isInteger()->coerce()->run());
	}

	public function testFloat() {
		$this->assertEquals(0, Structr::ize(null)->isFloat()->coerce()->run(), '', 0.0001);
		$this->assertEquals(3, Structr::ize(3)->isFloat()->coerce()->run(), '', 0.0001);
		$this->assertEquals(3.1415, Structr::ize(3.1415)->isFloat()->coerce()->run(), '', 0.0001);
		$this->assertEquals(0, Structr::ize(false)->isFloat()->coerce()->run(), '', 0.0001);
		$this->assertEquals(1, Structr::ize(true)->isFloat()->coerce()->run(), '', 0.0001);
		$this->assertEquals(3, Structr::ize("3")->isFloat()->coerce()->run(), '', 0.0001);
		$this->assertEquals(3.1415, Structr::ize("3.1415")->isFloat()->coerce()->run(), '', 0.0001);
		$this->assertEquals(3.1415, Structr::ize("3.1415foo")->isFloat()->coerce()->run(), '', 0.0001);
		$this->assertEquals(0, Structr::ize("foo3.1415")->isFloat()->coerce()->run(), '', 0.0001);
		$this->assertEquals(0, Structr::ize(array())->isFloat()->coerce()->run(), '', 0.0001);
		$this->assertEquals(1, Structr::ize(new \stdClass())->isFloat()->coerce()->run(), '', 0.0001);
	}

	public function testBoolean() {
		$this->assertSame(false, Structr::ize(null)->isBoolean()->coerce()->run(), "coerce null -> bool");
		$this->assertSame(true, Structr::ize(3)->isBoolean()->coerce()->run(), "coerce 3 -> bool");
		$this->assertSame(true, Structr::ize(3.1415)->isBoolean()->coerce()->run(), "coerce 3.1415 -> bool");
		$this->assertSame(false, Structr::ize(false)->isBoolean()->coerce()->run(), "coerce false -> bool");
		$this->assertSame(true, Structr::ize(true)->isBoolean()->coerce()->run(), "coerce true -> bool");
		$this->assertSame(true, Structr::ize("3")->isBoolean()->coerce()->run(), "coerce '3' -> bool");
		$this->assertSame(false, Structr::ize("0")->isBoolean()->coerce()->run(), "coerce '0' -> bool");
		$this->assertSame(true, Structr::ize("true")->isBoolean()->coerce()->run(), "coerce 'true' -> bool");
		$this->assertSame(true, Structr::ize("false")->isBoolean()->coerce()->run(), "coerce 'false' -> bool");
		$this->assertSame(true, Structr::ize("true foo")->isBoolean()->coerce()->run(), "coerce 'true foo' -> bool");
		$this->assertSame(true, Structr::ize("false foo")->isBoolean()->coerce()->run(), "coerce 'false foo' -> bool");
		$this->assertSame(true, Structr::ize("0 foo")->isBoolean()->coerce()->run(), "coerce '0 foo' -> bool");
		$this->assertSame(false, Structr::ize(array())->isBoolean()->coerce()->run(), "coerce array() -> bool");
		$this->assertSame(true, Structr::ize(new \stdClass())->isBoolean()->coerce()->run(), "coerce new stdClass() -> bool");
	}

	public function testString() {
		$this->assertSame("", Structr::ize(null)->isString()->coerce()->run(), "coerce null -> string");
		$this->assertSame("3", Structr::ize(3)->isString()->coerce()->run(), "coerce 3 -> string");
		$this->assertSame("3.1415", Structr::ize(3.1415)->isString()->coerce()->run(), "coerce 3.1415 -> string");
		$this->assertSame("", Structr::ize(false)->isString()->coerce()->run(), "coerce false -> string");
		$this->assertSame("1", Structr::ize(true)->isString()->coerce()->run(), "coerce true -> string");
		$this->assertSame("3f", Structr::ize("3f")->isString()->coerce()->run(), "coerce '3f' -> string");
		$this->assertSame("Array", Structr::ize(array())->isString()->coerce()->run(), "coerce array() -> string");
		$this->assertSame("Object", Structr::ize(new \stdClass())->isString()->coerce()->run(), "coerce new stdClass() -> string");
	}

}
