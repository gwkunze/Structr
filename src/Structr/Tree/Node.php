<?php

namespace Structr\Tree;

abstract class Node {
	private $parent = null;

	public function parent() {
		return $this->parent;
	}

	public function setParent(Node $parent) {
		$this->parent = $parent;
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
		return $this->root()->value();
	}

	public abstract function value($parentValue = null);

}
