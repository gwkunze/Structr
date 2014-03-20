<?php
/**
 * Copyright (c) 2012 Gijs Kunze
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Structr\Test;

use Structr\Structr;

class DeferredTest extends \PHPUnit_Framework_TestCase
{
    public function testDeferredSuccess() {
        Structr::clearAll();
        $structr = Structr::ize('foo')->is(function() {
            return Structr::define()->isString()->enum(array('foo', 'baz', 'bar'))->coerce();
        });

        $this->assertSame('foo', $structr->run());
    }

    public function testDeferredFail() {
        Structr::clearAll();
        $structr = Structr::ize('ban')->is(function() {
            return Structr::define()->isString()->enum(array('foo', 'baz', 'bar'))->coerce();
        });

        $this->setExpectedException('Structr\Exception', "'ban' not part of enum");
        $structr->run();
    }

    public function testDeferredInvalidReturnType() {
        Structr::clearAll();
        $structr = Structr::ize(4)->is(function() { return new \stdClass(); });

        $this->setExpectedException(
            'Structr\Exception',
            'Callable supplied to is() must return an instance of \Structr\Tree\Base\Node'
        );
        $structr->run();
    }
}
