<?php

namespace Structr\Tree\Base;

abstract class Node {
	private $parent = null;
	private $post = null;

	public function __construct($parent) {
		$this->setParent($parent);
	}

	public function parent() {
		return $this->parent;
	}

	public function setParent(Node $parent) {
		$this->parent = $parent;
	}

	public function post($function) {
		$this->post = $function;

		return $this;
	}

	public function root() {
		$return = $this;
		while(($parent = $return->parent()) != null) {
			$return = $parent;
		}
		return $return;
	}

	public function end() {
		return $this->parent();
	}

	public function run() {
		if($this->parent !== null) {
			return $this->root()->run();
		}

		$value = $this->getValue();

		$return = $this->_walk_value($value);

		return $this->_walk_post($return);
	}

	protected function _walk_value($value) {
		return $value;
	}

	protected function _walk_post($value) {

		if($this->post !== null) {
			$value = call_user_func($this->post, $value);
		}

		return $value;
	}

	protected function getValue() {
		return null;
	}
}
