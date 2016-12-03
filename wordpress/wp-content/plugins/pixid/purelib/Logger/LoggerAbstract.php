<?php

namespace PureLib\Logger;

abstract class LoggerAbstract {
	protected $_args;

	public function __construct($args = array()) {
		$this->_args = $args;
	}

	public function args($args = null) {
		return $this->_args;
	}

	abstract public function log($string);
}
