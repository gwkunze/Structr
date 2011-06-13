<?php
namespace Structr;

use Structr\Tree\RootNode;
use Structr\Tree\DefinitionNode;

use Structr\Exception;

class Structr {
	private static $definitions = array();

	public static function ize($variable) {
		return new RootNode($variable);
	}

	public static function izeJson($json) {
		return new RootNode(json_decode($json, true));
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
		self::$definitions = array();
	}

	public static function define($name) {
		$node = new DefinitionNode();
		self::$definitions[$name] = $node;

		return $node;
	}

	public static function getDefinition($name) {
		if(!isset(self::$definitions[$name]))
			throw new Exception("Structr definition '{$name}' does not exist");

		return self::$definitions[$name];
	}

	public static function getDefinitions($searchString) {
		$regexp = preg_replace_callback("/(?:(?<star>\*)|(?<other>[^\*]+))/", function($match) {
				if(!empty($match["star"])) {
					return "(.*)";
				}
				if(!empty($match["other"])) {
					return preg_quote($match["other"], "/");
				}
			}, $searchString);
		$regexp = "/^" . $regexp . "$/";
		
		$return = array();

		foreach(self::$definitions as $key => $value) {
			if(preg_match($regexp, $key)) {
				$return[] = $value;
			}
		}

		return $return;
	}
}