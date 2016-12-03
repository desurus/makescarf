<?php

namespace Pure\Request;
class Request extends \PureLib\Config\RawArray {

	/**
	 * We need to return uncleared variables! */
	public function __construct($data = array(), $defaults = array()) {
		parent::__construct($data, $defaults);	
	}
	public function get($var, $default = null) {
		$value = parent::get($var, $default);
		return $value;
	}
	/**
	 * This metho just return a raw array of storage object.
	 * @return array
	 */
	public function get_all() {
		return $this->_storage;
	}
}
