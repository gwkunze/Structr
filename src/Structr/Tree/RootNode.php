<?php

namespace Structr\Tree;

class RootNode extends Node {
	/** @var mixed Value to Structrize */
	private $value;

	/** @var \Structr\Tree\Node Childnode declaring type */
	private $child = null;

	public function __construct($value) {
		$this->value = $value;
	}

	public function value($parentValue = null) {
		if($this->child === null) {
			return $this->value;
		}

		return $this->child->value($this->value);
	}

	/**
	 * @return \Structr\Tree\IntegerNode
	 */
	public function isInteger() {
		$this->child = new IntegerNode($this);

		return $this->child;
	}

	/**
	 * @return \Structr\Tree\FloatNode
	 */
	public function isFloat() {
		$this->child = new FloatNode($this);

		return $this->child;
	}

	/**
	 * @return \Structr\Tree\BooleanNode
	 */
	public function isBoolean() {
		$this->child = new BooleanNode($this);

		return $this->child;
	}

	/**
	 * @return \Structr\Tree\StringNode
	 */
	public function isString() {
		$this->child = new StringNode($this);
		
		return $this->child;
	}

	/**
	 * @return \Structr\Tree\NullNode
	 */
	public function isNull() {
		$this->child = new NullNode($this);

		return $this->child;
	}

	public function end() {
		return $this->value();
	}
}
