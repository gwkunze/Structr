<?php

namespace Structr\Tree\Composite;

use Structr\Tree\Base\Node;

use Structr\Exception;

class MapNode extends Node {
	private $keys = array();
	private $strict = false;

	/**
	 * @param string $keyname
	 * @return \Structr\Tree\Composite\MapKeyNode
	 *
	 */
	public function key($keyname) {
		$this->keys[$keyname] = new MapKeyNode($this);
		$this->keys[$keyname]->setName($keyname);

		return $this->keys[$keyname];
	}

	public function strict() {
		$this->strict = true;
		return $this;
	}

	public function value($parentValue = null) {
		$value = $parentValue;

		if(!is_array($value)) {
			throw new Exception("Invalid type '" . gettype($value) . "', expecting 'map' (associative array)");
		}

		$return = array();

		foreach($this->keys as $key => $val) {
			if(isset($value[$key])) {
				$return[$key] = $val->value($value[$key]);
			} else {
				$return[$key] = $val->valueUnset();
			}
			if($this->strict) {
				unset($value[$key]);
			}
		}

		if($this->strict && count($value)) {
			throw new Exception("Unexpected key(s) " . implode(', ', array_keys($value)));
		}
		return $return;
	}
}
