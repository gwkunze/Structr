<?php

namespace Structr\Test\Scalar;

use Structr\Structr;

class IntegerTest extends \PHPUnit_Framework_TestCase {

	public function testCompare() {
		$value = 3;

		$expected = $value;

		$this->assertSame($expected, Structr::ize($value)->isInteger()->gt(2)->end()->value());
		$this->assertSame($expected, Structr::ize($value)->isInteger()->gte(3)->end()->value());

		$this->assertSame($expected, Structr::ize($value)->isInteger()->lt(4)->end()->value());
		$this->assertSame($expected, Structr::ize($value)->isInteger()->lte(3)->end()->value());
	}

	public function testClamp() {
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
					->valuePrototype()
						->isInteger()->gte(9, true)->end()
					->endPrototype()
				->endKey()
				->key("lte")
					->valuePrototype()
						->isInteger()->lte(2, true)->end()
					->endPrototype()
				->endKey()
				->key("clamp")
					->valuePrototype()
						->isList()
							->listPrototype()
								->isInteger()->clamp(3, 7)->end()
							->endPrototype()
						->end()
					->endPrototype()
				->endKey()
			->end()
			->value()
		;

		$this->assertSame($expected, $result);
	}
}
