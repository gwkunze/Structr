<?php

namespace Structr\Tree\Base;

use Structr\Tree\Scalar\IntegerNode;
use Structr\Tree\Scalar\FloatNode;
use Structr\Tree\Scalar\BooleanNode;
use Structr\Tree\Scalar\NullNode;
use Structr\Tree\Scalar\StringNode;

use Structr\Tree\Composite\ListNode;
use Structr\Tree\Composite\MapNode;
use Structr\Tree\Composite\ChoiceNode;

abstract class PrototypeNode extends Node {
	/** @var \Structr\Tree\Base\Node Child node declaring type */
	private $prototype = null;

	/**
	 * @return Node
	 */
	protected function getPrototype() {
		return $this->prototype;
	}

	/**
	 * @return \Structr\Tree\Scalar\IntegerNode
	 */
	public function isInteger() {
		$this->prototype = new IntegerNode($this);

		return $this->prototype;
	}

	/**
	 * @return \Structr\Tree\Scalar\FloatNode
	 */
	public function isFloat() {
		$this->prototype = new FloatNode($this);

		return $this->prototype;
	}

	/**
	 * @return \Structr\Tree\Scalar\BooleanNode
	 */
	public function isBoolean() {
		$this->prototype = new BooleanNode($this);

		return $this->prototype;
	}

	/**
	 * @return \Structr\Tree\Scalar\StringNode
	 */
	public function isString() {
		$this->prototype = new StringNode($this);

		return $this->prototype;
	}

	/**
	 * @return \Structr\Tree\Scalar\NullNode
	 */
	public function isNull() {
		$this->prototype = new NullNode($this);

		return $this->prototype;
	}

	/**
	 * @return \Structr\Tree\Composite\ListNode
	 */
	public function isList() {
		$this->prototype = new ListNode($this);

		return $this->prototype;
	}

	/**
	 * @return \Structr\Tree\Composite\MapNode
	 */
	public function isMap() {
		$this->prototype = new MapNode($this);

		return $this->prototype;
	}

	/**
	 * @return \Structr\Tree\Composite\ChoiceNode
	 */
	public function isChoice() {
		$this->prototype = new ChoiceNode($this);

		return $this->prototype;
	}

	public function end() {
		return $this->parent();
	}

	public function value($parentValue = null) {
		return $this->getPrototype()->value($parentValue);
	}
}
