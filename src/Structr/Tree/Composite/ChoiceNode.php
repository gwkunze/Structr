<?php

namespace Structr\Tree\Composite;

use Structr\Tree\Base\Node;

use Structr\Tree\Composite\ChoicePrototypeNode;
use Structr\Exception;

class ChoiceNode extends Node {
	private $alternatives = array();

	public function addAlternative($alternative) {
		$this->alternatives[] = $alternative;
	}

	public function altPrototype() {
		$prototype = new ChoicePrototypeNode($this);

		$this->alternatives[] = $prototype;

		return $prototype;
	}

	public function value($parentValue = null) {
		$value = $parentValue;

		foreach($this->alternatives as $alternative) {
			try {
				return $alternative->value($value);
			} catch(Exception $e) {
			}
		}

		throw new Exception("No alternative matching type '" . gettype($value) . "'");
	}
}
