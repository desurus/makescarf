<?php

namespace Pure\Settings;
abstract class Container {
	protected $_container;
	protected $_widget;
	public function __construct($widget = null)	 {
		$this->_init();	
	}

	public function add($element) {
		$this->_container[] = $element;
	}
	public function set_widget($widget) {
		$this->_widget = $widget;
		return $this;
	}
	public function get_widget() {
		return $this->_widget;
	}

	public function get_items() {
		return $this->_container;
	}
	/*
	 * This method sets the single config param with a provided value.
	 * @param string $name The name of a config key.
	 * @param mixed $value The new value of a config key. 
	 * @return boolean
	 * **/
	public function set_value($name, $value) {
		if(!is_string($name) || empty($name)) return false;
		foreach($this->_container as $element) {
			if($element->get_name() == $name) {	
				$element->set_value($value);
				return true;
			}
		}
		return false;
	}
	/*
	 * This method sets the current container items values to a provided values in $values array
	 * @param array $values An array with new key => value data for object in this container.
	 * @return boolean
	 * **/
	public function set_values($values) {
		if(empty($values) || !is_array($values)) return false;
		foreach($values as $key => $value) {
			$this->set_value($key, $value);	
		}
	}
	/*
	 * This method save the current settings...
	 * @param \Pure\Widget\Settings\CodeFinder $code_finder An initialized object with finded data about this widget call.
	 * @return boolean
	 * **/
	public function save(\Pure\Widget\Settings\CodeFinder $code_finder) {
		//FIXME: We need to check the initialized $code_finder object.
		\Pure\Debug::dump($code_finder);
	}

	abstract protected function _init();
}
