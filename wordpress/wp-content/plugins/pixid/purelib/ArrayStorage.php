<?php

namespace PureLib;
abstract class ArrayStorage implements \ArrayAccess {
	protected $_storage;
	public function __construct($params = array(), $defaults = array()) {
		if(($params instanceof ArrayStorage)) $params = $params->to_array();
		if(($defaults instanceof ArrayStorage)) $defaults = $defaults->to_array();
		if(null === $params) $params = array();
		if(null === $defaults) $defaults = array();

		if(!is_array($params) || !is_array($defaults)) throw new \Exception("Args must be a valid arrays.");
		
		$params = array_merge($defaults, $params);
		$this->set_params($params);
	}
	public function offsetExists($offset) {
		return $this->exists($offset);
	}
       	public function offsetGet($offset) {
		return $this->get($offset, null);
	}
	public function offsetSet($offset, $value) {
		return $this->set($offset, $value);
	}
	public function offsetUnset($offset) {
		unset($this->_storage[$offset]);
	}	
	public function exists($variable) {
		if(!isset($this->_storage[$variable])) return false;
		return true;
	}
	/**
	 * TODO: Need a small doc for this method while version 0.0.21*/
	public function get($name, $default = null) {
		if(is_array($name)) {
			if(empty($name)) return array();
			$result = array();
			foreach($name as $key) {
				$result[$key] = $this->get($key);
			}
			return $result;
		}
		if(!isset($this->_storage[$name])) return $default;
		return $this->_storage[$name];	
	}
	public function set($name, $value = null) {
		$this->_storage[$name] = $value;
	}
	public function set_params($params) {
		$this->_storage = $params;
	}
	public function extend($params) {
		if(($params instanceof Config)) $params = $params->to_array();
		$args = array_merge($params, $this->to_array());
		$class = get_class($this);
		return new $class($args);
	}
	public function to_array() {
		return $this->_storage;
	}

	public function get_array($keys = null) {
		if($keys == null) return $this->to_array();
		$result = array();
		foreach($keys as $key) {
			$value = $this->get($key, null);
			if(null === $value) continue;
			$result[$key] = $value;
		}
		return $result;
	}
}
