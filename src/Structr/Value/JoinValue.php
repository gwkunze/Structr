<?php

namespace Structr\Value;

class JoinValue extends Value {
	private $base;
	private $other;

	public function __construct(Value $base, Value $other) {
		$this->base = $base;
		$this->other = $other;
	}

	public function values() {
		return array_merge($this->base->values(), $this->other->values());
	}

	public function parent() {
		return null;
	}

	public function __toString() {
		return '((' . $this->base . ') || (' . $this->other . '))';
	}
}
