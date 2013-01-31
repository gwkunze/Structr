<?php
/**
 * Copyright (c) 2012 Gijs Kunze
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Structr\Test;

use Structr\Structr;

class NodeIdTest extends \PHPUnit_Framework_TestCase
{

    public function testSimpleId() {
        $array = array(1, 2, "3");

        $expected = array(1, 2, 3);

        $structure = Structr::ize($array);

        $structure->isList()->setId("listnode");

        $structure->get("listnode")->item()->setId("listitemdef");

        $this->assertSame($structure->get("listnode")->get("listitemdef"), $structure->get("listitemdef"));

        $structure->get("listitemdef")->isInteger()->coerce();

        $result = $structure->run();

        $this->assertSame($expected, $result);
    }

}