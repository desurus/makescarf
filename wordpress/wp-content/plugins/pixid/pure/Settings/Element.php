<?php

namespace Pure\Settings;
abstract class Element {
	protected $_args;
	protected $_title;
	protected $_name;
	protected $_id;
	protected $_value;
	protected $_placeholder;
	protected $_description;
	protected $_default_value;

	public function __construct($args = array()) {
		$this->set_args($args);
		$args = $this->get_args();
		if($args->get('title')) {
			$this->set_title($args->get('title'));
		}
		if($args->get('name')) {
			$this->set_name($args->get('name'));
		}
		if($args->get('id')) {
			$this->set_id($args->get('id'));
		}
		if($args->get('value')) {
			$this->set_value($args->get('value'));
		}
		if($args->get('placeholder')) {
			$this->set_placeholder($args->get('placeholder'));
		}
		if($args->get('description')) {
			$this->set_description($args->get('description'));
		}
		if($args->get('default_value')) {
			$this->set_default_value($args->get('default_value'));
		}
		$this->_parse_args();
	}	
	public function set_args($args) {
		$this->_args = $args;
		return $this;
	}
	public function get_args() {
		//FIXME: This is a little overhead!
		return new \PureLib\Config\RawArray($this->_args);
	}

	public function set_title($title) {
		$this->_title = $title;
		return $this;
	}
	public function get_title() {
		return $this->_title;
	}
	public function set_name($name) {
		$this->_name = $name;
		return $this;
	}
	public function get_name() {
		return $this->_name;
	}
	public function set_id($id) {
		$this->_id = $id;
		return $this;
	}
	public function get_id() {
		if(empty($this->_id)) {
			$this->set_id($this->get_name());
		}
		return $this->_id;
	}
	public function set_value($value) {
		$this->_value = $value;
		return $this->_value;
	}
	public function get_value() {
		if(null === $this->_value) {
			return $this->get_default_value();
		}
		return $this->_value;
	}
	public function set_placeholder($placeholder) {
		$this->_placeholder = $placeholder;
		return $this;
	}
	public function get_placeholder() {
		return $this->_placeholder;
	}
	public function set_description($description) {
		$this->_description = $description;
		return $this;
	}
	public function get_description() {
		return $this->_description;
	}
	public function set_default_value($value) {
		$this->_default_value = $value;
		return $this;
	}
	public function get_default_value() {
		return $this->_default_value;
	}
	public function get_html_classes() {
		return "";	
	}
	protected function _parse_args() {
		
	}
	abstract public function get_html();
	public function render() {
		return $this->get_html();
	}
}
