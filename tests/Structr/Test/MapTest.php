<?php

namespace Structr\Test;

use Structr\Structr;

class MapTest extends \PHPUnit_Framework_TestCase {

	public function testSimpleMap() {
		$array = array(
			"foo" => "bar",
			"baz" => 3.1416,
		);

		$result = Structr::ize($array)
			->isMap()
			->beginPrototype()
				->key("foo")
			->endPrototype()
			->run()
		;

		var_dump($result);
	}

}
