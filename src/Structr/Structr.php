<?php
namespace Structr;

use Structr\Exception;

use Structr\Tree\RootNode;

/**
 * Structr base class
 */
class Structr
{
	/**
	 * @var type array Map of all definitions
	 */
    private static $_definitions = array();

	/**
	 * Structrize a given variable
	 * @param type $variable The variable to Structrize
	 * @return \Structr\Tree\RootNode A rootnode on which to define Structr commands
	 */
    public static function ize($variable)
	{
        return new RootNode($variable);
    }

	/**
	 * Sturctr::ize a given JSON encode variable
	 * @param type $json A JSON encode variable
	 * @return \Structr\Tree\RootNode A rootnode on which to define Structr commands
	 */
    public static function izeJson($json)
	{
        return self::ize(self::json_decode($json, true));
    }

	/**
	 * Structr::ize $_GET
	 * @return \Structr\Tree\RootNode A rootnode on which to define Structr commands
	 */
    public static function get()
	{
        return self::ize($_GET)->isMap();
    }

	/**
	 * Structr::ize $_POST
	 * @return \Structr\Tree\RootNode A rootnode on which to define Structr commands
	 */
    public static function post()
	{
        return self::ize($_POST)->isMap();
    }

	/**
	 * Structr::ize $_REQUEST
	 * @return \Structr\Tree\RootNode A rootnode on which to define Structr commands
	 */
    public static function request()
	{
        return self::ize($_REQUEST)->isMap();
    }

	/**
	 * Structr::ize $_SESSION
	 * @return \Structr\Tree\RootNode A rootnode on which to define Structr commands
	 */
    public static function session()
	{
        return self::ize($_SESSION)->isMap();
    }

	/**
	 * Create a new Structr definition
	 * A definition is first defined as you would use ize(), but 
	 * and then can then retrieved using Structr::getDefinition so
	 * you can pass a Structr definition around in your code. For
	 * example to define an input- and/or output spec for your API.
	 * 
	 * @param type $name
	 * @return \Structr\Tree\RootNode
	 */
    public static function define($name = null) {
        $node = static::ize(null);
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
		$structr =
			static::define($className)
            ->pre(function($s) { return (array) $s; })
            ->isMap();
		
        $reflect = new \ReflectionClass($className);

        foreach ($reflect->getProperties(\ReflectionProperty::IS_PUBLIC) as $prop) {
            $structr->key($prop->getName())->optional()->isAny()->end();
        }

        return $structr;
    }
	
	/**
	 * Get a Structr object that was defined earlier
	 * 
	 * @param type $name The name of definition to find
	 * @return type 
	 * @throws Exception
	 */
	public static function getDefinition($name) {
        if(!isset(self::$_definitions[$name])) {
            throw new Exception("Structr definition '{$name}' does not exist");
		}

        return self::$_definitions[$name];
    }

	/**
	 * Find Structr objects matching a search string
	 * 
	 * @param type $searchString The string used to find definitions
	 * @return type array An array of definitions matching $searchString
	 */
    public static function getDefinitions($searchString) {
        $expression = preg_replace_callback(
            '/(?:(?<star>\*)|(?<other>[^\*]+))/',
            function($match) {
                if (!empty($match['star'])) {
                    return '(.*)';
                }
                if (!empty($match['other'])) {
                    return preg_quote($match['other'], '/');
                }
            },
			$searchString
        );
        $regexp = "/^{$expression}$/";

		$return = array();
		foreach(self::$_definitions as $name => $definition) {
			if (preg_match($regexp, $name)) {
				$return[$name] = $definition;
			}
		}
		
		return $return;
    }
	
	/**
	 * Clear all definitions defined so far
	 */
    public static function clearAll()
	{
        self::$_definitions = array();
    }
    
    /**
	 * Helper function to JSON decode a variable
	 * Throws exception on erorr; PHP's builtin json_decode doesn't do this
	 * 
	 * @param type $value The value to decode
	 * @return type array A json_decode'd version of the input
	 * @throws Exception
	 */
    public static function json_decode($value)
    {
        $value = @json_decode($value, true);
        $error = json_last_error();
        
        if ($error !== JSON_ERROR_NONE)
        {
            // JSON_ERROR_UTF8 is PHP >= 5.3.3
            // Define it in case it isn't there to prevent notices
            defined('JSON_ERROR_UTF8') || define('JSON_ERROR_UTF8', 5);
            
            switch ($error) {
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
					throw new Exception('Uknown error in json_decode ('.$error.')');
            }
        }
        
        return $value;
    }
}
