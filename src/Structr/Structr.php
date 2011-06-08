<?php
namespace Structr;

use Structr\Value\Value;

class Structr {
	public static function GET() {
		return new Value($_GET, '_GET');
	}

	public static function POST() {
		return new Value($_POST, '_POST');
	}

	public static function REQUEST() {
		return new Value($_REQUEST, '_REQUEST');
	}

	public static function COOKIE() {
		return new Value($_COOKIE, '_COOKIE');
	}

	public static function FILES() {
		return new Value($_FILES, '_FILES');
	}

	public static function ENV() {
		return new Value($_ENV, '_ENV');
	}

	public static function SERVER() {
		return new Value($_SERVER, '_SERVER');
	}

	public static function SESSION() {
		return new Value($_SESSION, '_SESSION');
	}

	public static function CUSTOM($value) {
		return new Value($value);
	}
}