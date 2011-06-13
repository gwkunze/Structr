<?php

namespace Structr\Tree\Composite;

use Structr\Tree\Base\Node;

use Structr\Exception;

class ListNode extends Node {
	/** @var ListPrototypeNode */
	private $listPrototype = null;

	private $minimumSize = null;
	private $maximumSize = null;

	public function listPrototype() {
		$this->listPrototype = new ListPrototypeNode($this);

		return $this->listPrototype;
	}

	public function minSize($size) {
		$this->minimumSize = $size;

		return $this;
	}

	public function maxSize($size) {
		$this->maximumSize = $size;

		return $this;
	}

	public function _walk_value($value = null) {
		$value = parent::_walk_value($value);

		if($this->listPrototype === null) {
			throw new Exception("List without prototype");
		}

		if(!is_array($value)) {
			throw new Exception("Invalid type '" . gettype($value) . "', expecting 'list' (numerical array)");
		}

		$return = array();

		$length = count($value);

		if($this->minimumSize !== null) {
			if($length < $this->minimumSize)
				throw new Exception("List smaller than minimum size");
		}

		if($this->maximumSize !== null) {
			if($length > $this->maximumSize)
				throw new Exception("List larger then maximum size");
		}

		for($i = 0;$i < $length;$i++) {
			if(!isset($value[$i]))
				throw new Exception("Invalid list, missing index '{$i}'. Might be a map.");
			$return[] = $this->listPrototype->_walk_post($this->listPrototype->_walk_value($value[$i]));
		}
		return $return;
	}
}
