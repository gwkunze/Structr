<?php

namespace Structr\Test;

use Structr\Structr;

class MapTest extends \PHPUnit_Framework_TestCase {

	public function testSimpleMap() {
		$array = array(
			"id" => "35",
			"baz" => "3.1416",
			"foo" => "bar"
		);

		$expected = array(
			"id" => 35,
			"baz" => 3.1416
		);

		$result = Structr::ize($array)
			->isMap()
				->key("id")
					->valuePrototype()
						->isInteger()->coerce()->end()
					->endPrototype()
				->endKey()
				->key("baz")
					->valuePrototype()
						->isFloat()->coerce()->end()
					->endPrototype()
				->endKey()
			->end()
			->run()
		;

		$this->assertSame($expected, $result);
	}

	public function testSimpleMapFail() {
		$this->setExpectedException("\\Structr\\Exceptions\\MissingKeyException");

		$array = array(
			"id" => 135,
		);

		Structr::ize($array)
			->isMap()
				->key("id")
					->valuePrototype()
						->isInteger()->end()
					->endPrototype()
				->endKey()
				->key("baz")
					->valuePrototype()
						->isFloat()->end()
					->endPrototype()
				->endKey()
			->end()
			->run()
		;
	}

	public function testStrictMap() {
		$array = array(
			"id" => 12,
			"name" => "foo"
		);

		$expected = $array;

		$result = Structr::ize($array)
			->isMap()
				->strict()
				->key("id")
					->valuePrototype()
						->isInteger()->end()
					->endPrototype()
				->endKey()
				->key("name")
					->valuePrototype()
						->isString()->end()
					->endPrototype()
				->endKey()
			->end()
			->run()
		;

		$this->assertSame($expected, $result);
	}

	public function testStrictMapFail() {
		$this->setExpectedException("\\Structr\\Exceptions\\UnexpectedKeyException");

		$array = array(
			"id" => 12,
			"name" => "foo",
			"superfluousExtraKey" => "bar"
		);

		Structr::ize($array)
			->isMap()
				->strict()
				->key("id")
					->valuePrototype()
						->isInteger()->end()
					->endPrototype()
				->endKey()
				->key("name")
					->valuePrototype()
						->isString()->end()
					->endPrototype()
				->endKey()
			->end()
			->run()
		;
	}

	public function testMapDefault() {
		$array = array(
			"id" => 123,
			"foo" => "bar"
		);

		$expected = array(
			"id" => 123,
			"foo" => "bar",
			"baz" => "ban"
		);

		$result = Structr::ize($array)
			->isMap()
				->key("id")
					->valuePrototype()
						->isInteger()->end()
					->endPrototype()
				->endKey()
				->key("foo")
					->valuePrototype()
						->isString()->end()
					->endPrototype()
				->endKey()
				->key("baz")
					->defaultValue("ban")
					->valuePrototype()
						->isString()->end()
					->endPrototype()
				->endKey()
			->end()
			->run()
		;

		$this->assertSame($expected, $result);
	}

}
