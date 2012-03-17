<?php
namespace Structr;

use Structr\Tree\RootNode;
use Structr\Tree\DefinitionNode;

use Structr\Exception;

class Structr
{
    private static $_definitions = array();

    public static function ize($variable) {
        return new RootNode($variable);
    }

    public static function izeJson($json) {
        return new RootNode(self::json_decode($json, true));
    }

    public static function get() {
        return self::ize($_GET)->isMap();
    }

    public static function post() {
        return self::ize($_POST)->isMap();
    }

    public static function request() {
        return self::ize($_REQUEST)->isMap();
    }

    public static function session() {
        return self::ize($_SESSION)->isMap();
    }

    public static function clearAll() {
        self::$_definitions = array();
    }

    public static function define($name = null) {
        $node = new DefinitionNode();
        if (!empty($name)) {
            self::$_definitions[$name] = $node;
        }

        return $node;
    }

    /**
     * Given a class name, define a Structr which exports all its
     * public properties as a map.
     */
    public static function defineFromClass($className)
    {
        $reflect = new \ReflectionClass($className);
        $structr = static::define($className)
            ->pre(function($s) { return (array)$s; })
            ->isMap();

        foreach ($reflect->getProperties(\ReflectionProperty::IS_PUBLIC) as $prop)
        {
            $structr->key($prop->getName())->optional()->isAny()->end();
        }

        return $structr;
    }


    public static function getDefinition($name) {
        if(!isset(self::$_definitions[$name]))
            throw new Exception("Structr definition '{$name}' does not exist");

        return self::$_definitions[$name];
    }

    public static function getDefinitions($searchString) {
        $regexp = preg_replace_callback("/(?:(?<star>\*)|(?<other>[^\*]+))/",
            function($match) {
                if (!empty($match["star"])) {
                    return "(.*)";
                }
                if (!empty($match["other"])) {
                    return preg_quote($match["other"], "/");
                }
            }, $searchString);
        $regexp = "/^" . $regexp . "$/";

        $return = array();

        foreach (self::$_definitions as $key => $value) {
            if (preg_match($regexp, $key)) {
                $return[] = $value;
            }
        }

        return $return;
    }
    
    public static function json_decode($value)
    {
        $value = @json_decode($value, true);
        $error = json_last_error();
        
        if ($error !== JSON_ERROR_NONE)
        {
            // JSON_ERROR_UTF8 is PHP >= 5.3.3
            // Let's define it in case it isn't there
            // to prevent notices
            defined('JSON_ERROR_UTF8') || define('JSON_ERROR_UTF8', 5);
            
            switch ($error)
            {
            case JSON_ERROR_DEPTH:
                throw new Exception('The maximum stack depth has been exceeded');
            case JSON_ERROR_STATE_MISMATCH:
                throw new Exception('Invalid or malformed JSON');
            case JSON_ERROR_CTRL_CHAR:
                throw new Exception('Control character error, possibly incorrectly encoded');
            case JSON_ERROR_SYNTAX:
                throw new Exception('Syntax error');
            case JSON_ERROR_UTF8:
                throw new Exception('Malformed UTF-8 characters, possibly incorrectly encoded');
            default:
                throw new Exception('Uknown error on json_decode');
            }
        }
        
        return $value;
    }
}