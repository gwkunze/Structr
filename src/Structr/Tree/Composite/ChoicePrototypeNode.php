<?php

namespace Structr\Tree\Composite;

use Structr\Tree\Base\PrototypeNode;

class ChoicePrototypeNode extends PrototypeNode {

	public function endPrototype() {
		return $this->end();
	}
}
