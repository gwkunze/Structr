<?php
namespace Structr;

use Structr\Tree\RootNode;

class Structr {
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
}