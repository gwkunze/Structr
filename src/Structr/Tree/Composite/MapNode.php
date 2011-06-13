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

	public function _walk_value($value) {
		$value = parent::_walk_value($value);

		if(!is_array($value)) {
			throw new Exception("Invalid type '" . gettype($value) . "', expecting 'map' (associative array)");
		}

		$return = array();

		foreach($this->keys as $key => $val) {
			if(isset($value[$key])) {
				$return[$key] = $val->_walk_post($val->_walk_value($value[$key]));
			} else {
				$return[$key] = $val->_walk_post($val->_walk_value_unset());
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
