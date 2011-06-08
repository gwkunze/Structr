<?php

namespace Structr\Test;

use Structr\Structr;

class ScalarTest extends \PHPUnit_Framework_TestCase {
	public function testInteger() {
		$variable = 3;

		$this->assertEquals(Structr::ize($variable)->integer(), $variable);
	}
}