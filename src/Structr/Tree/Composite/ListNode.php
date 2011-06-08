<?php

namespace Structr\Tree\Composite;

use Structr\Tree\Base\Node;

use Structr\Exceptions\PrototypeUndefinedException;
use Structr\Exceptions\InvalidTypeException;
use Structr\Exceptions\ListTooSmallException;
use Structr\Exceptions\ListTooLargeException;

class ListNode extends Node {
	/** @var ListPrototypeNode */
	private $listPrototype = null;

	private $minimumSize = null;
	private $maximumSize = null;

	public function beginPrototype() {
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

	public function value($parentValue = null) {
		$value = $parentValue;

		if($this->listPrototype === null) {
			throw new PrototypeUndefinedException("List without prototype");
		}

		if(!is_array($value)) {
			throw new InvalidTypeException("Invalid type '" . gettype($value) . "', expecting 'list' (numerical array)");
		}

		$return = array();

		$length = count($value);

		if($this->minimumSize !== null) {
			if($length < $this->minimumSize)
				throw new ListTooSmallException("List smaller than minimum size");
		}

		if($this->maximumSize !== null) {
			if($length > $this->maximumSize)
				throw new ListTooLargeException("List larger then maximum size");
		}

		for($i = 0;$i < $length;$i++) {
			if(!isset($value[$i]))
				throw new InvalidTypeException("Invalid list, missing index '$i'. Might be a map.");
			$return[] = $this->listPrototype->value($value[$i]);
		}
		return $return;
	}
}
