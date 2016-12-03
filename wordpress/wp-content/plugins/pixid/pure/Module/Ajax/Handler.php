<?php

namespace Pure\Module\Ajax;
class Handler {
	protected $_widget;
	public function __construct($widget) {
		$this->_widget = $widget;
	}
	public function Session() {
		return \Pure\Session::instance();
	}
	public function request() {
		return \Pure\Request::instance();
	}
	public function get_request() {
		return $this->request();
	}
	public function widget() {
		return $this->_widget;
	}
}
