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
}