<?php
/**
 * This class may help to provide a data to a widget templates, so we can easy explore the provided data to a Widget template...
 * */
namespace Pure\Template;
class Data implements \ArrayAccess {
	public $container = array();
	public function __construct($data = array()) {
		$this->set_data($data);	
	}
	public function set_data($data) {
		$this->container = $data;
		return $this;
	}
	public function add($data_key, $data_value) {
		$this->container[$data_key] = $data_value;
	}
	public function get($data_key, $default = null) {
		if(!isset($this->container[$data_key])) return $default;
		return $this->container[$data_key];
	}
	public function remove($name) {
		unset($this->container[$name]);
	}
	public function get_array() {
		return $this->container;
	}
	/**
	 * This method helps developers who works just with templates.
	 * So, you can print all avaliable data assigned by current widget via $data->print_array() in your template files.
	 * */
	public function print_array() {
		\Pure\Debug::dump($this->container);	
	}
	public function print_all() {
		//TODO:
	}
	
	public function offsetSet($offset, $value) {
		if (is_null($offset)) {
			$this->container[] = $value;
		} else {
			$this->container[$offset] = $value;
		}
	}

	public function offsetExists($offset) {
		return isset($this->container[$offset]);
	}

	public function offsetUnset($offset) {
		unset($this->container[$offset]);
	}

	public function offsetGet($offset) {
		return isset($this->container[$offset]) ? $this->container[$offset] : null;
	}
}
