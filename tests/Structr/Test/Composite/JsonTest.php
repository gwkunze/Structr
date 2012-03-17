<?php

namespace Structr\Test\Composite;

use Structr\Structr;

class JsonTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Simple integer list, json_encoded.
     */
    public function testSimpleJsonListIntegers() {
        $array = array(1, 2, 3, 4, 5);
        $input = json_encode($array);
        
        $result = Structr::ize($input)
            ->isJsonList()
            ->item()
                ->isInteger()->end()
            ->endItem()
            ->run();

        $this->assertSame($array, $result);
    }
    
    /**
     * Simple string list, json_encoded.
     */
    public function testSimpleJsonListStrings() {
        $array = array('a', 'b', 'c', 'd', 'e');
        $input = json_encode($array);
        
        $result = Structr::ize($input)
            ->isJsonList()
            ->item()
                ->isString()->end()
            ->endItem()
            ->run();

        $this->assertSame($array, $result);
    }
    
    /**
     * Simple mixed map
     */
    public function testSimpleJsonMap() {
        $array = array('a' => 1, 'b' => 3.14, 'c' => 'c');
        $input = json_encode($array);
        
        $result = Structr::ize($input)
                ->isJsonMap()
                    ->key('a')->isInteger()->end()->endKey()
                    ->key('b')->isFloat()->end()->endKey()
                    ->key('c')->isString()->end()->endKey()
                ->run();
        
        $this->assertSame($array, $result);
        
    }
    
    /**
     * Nested array
     */
    public function testNestedJson() {
        $array = array(1, 2, 3, 4, 5, array(1, 2, 3));
        $input = json_encode($array);
        
        $result = Structr::ize($input)
            ->isJsonList()
            ->item()
                ->isAny()->end()
            ->endItem()
            ->run();
        
        $this->assertSame($array, $result);
    }
    
    /**
     * Test nesting depth
     */
    public function testNestingDepth() {
        $input = '{{{{{{{{{{{{{{{{{{{{{{{{{{{{{{a: 1}}}}}}}}}}}}}}}}}}}}}}}}}}}}}}';
        
        $this->setExpectedException(
            '\Structr\Exception', 'Syntax error'
        );
                
        Structr::ize($input)
            ->isJsonList()
            ->item()
                ->isAny()->end()
            ->endItem()
            ->run();
        
        $this->fail('Should have raised an exception');
    }
    
    /**
     * Partially JSON
     */
    public function testPartialJson()
    {
        $c = array('d', 'e');
        $array = array('a'=>'a', 'b'=>$c);
        $input = array('a'=>'a', 'b'=>json_encode($c));
        
        $result = Structr::ize($input)
            ->isMap()
                ->key('a')
                    ->isString()->end()
                ->endKey()
                ->key('b')
                    ->isJsonList()
                        ->item()
                            ->isString()->end()
                        ->endItem()
                    ->end()
                ->endKey()
           ->run();
        
        $this->assertSame($array, $result);
    }
        
    /**
     * Use ::define() instead of ::ize
     */
    public function testDefined() {
        Structr::clearAll();
        
        $array = array(1, 2, 3, 4, 5);
        $input = json_encode($array);
        
        Structr::define('test')
            ->isJsonList()
                ->item()
                    ->isAny()->end()
                ->endItem()
            ->end();
        
        $result = Structr::ize($input)->is('test')->run();

        $this->assertSame($array, $result);
    }
    
    /**
     * Test malformed JSON
     */
    public function testMalformedJson() {
        $input = '{"a":1}}';
        
        $this->setExpectedException(
            '\Structr\Exception', 'Invalid or malformed JSON'
        );
                
        Structr::ize($input)
            ->isJsonMap()
            ->run();
    }
    
    /**
     * Test syntax error
     */
    public function testSyntaxError() {
        $input = '{a:1}';
        
        $this->setExpectedException(
            '\Structr\Exception', 'Syntax error'
        );
                
        Structr::ize($input)
            ->isJsonMap()
            ->run();
    }
    
    
}
