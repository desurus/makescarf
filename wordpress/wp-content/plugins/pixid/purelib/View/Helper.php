<?php

namespace PureLib\View;

class Helper {
	protected $_name;
	protected $_view;
	public function __construct() {
	
	}
	
	public function set_name($name) {
		$this->_name = $name;
		return $this;
	}
	public function get_name() {
		return $this->_name;
	}
	public function set_view($view) {
		$this->_view = $view;
		return $this;
	}
	public function get_view() {
		return $this->_view;
	}
		
	public function render($name, $assign = array()) {
		$helper_name = $this->get_name();
		$path = "helpers/{$helper_name}/{$name}";
		return $this->get_view()->render($path, $assign);
	}	
}
