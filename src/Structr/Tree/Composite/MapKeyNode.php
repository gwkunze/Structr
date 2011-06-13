<?php

namespace Structr\Tree\Composite;

use Structr\Tree\Base\Node;

use Structr\Exception;

class MapKeyNode extends Node {

	/** @var \Structr\Tree\Composite\MapKeyPrototypeNode */
	private $keyPrototype = null;

	private $required = true;
	private $defaultValue = null;

	private $name;

	public function setName($name) {
		$this->name = $name;
	}

	public function getName() {
		return $this->name;
	}

	/**
	 * @return \Structr\Tree\Composite\MapKeyPrototypeNode
	 */
	public function valuePrototype() {
		$this->keyPrototype = new MapKeyPrototypeNode($this);

		return $this->keyPrototype;
	}

	public function defaultValue($value) {
		$this->required = false;
		$this->defaultValue = $value;

		return $this;
	}

	public function endKey() {
		return $this->parent();
	}

	public function _walk_value($value) {
		$value = parent::_walk_value($value);
		return $this->keyPrototype->_walk_post($this->keyPrototype->_walk_value($value));
	}

	public function _walk_value_unset() {
		if($this->required) {
			throw new Exception("Missing key '{$this->name}'");
		}

		return $this->defaultValue;
	}
}
