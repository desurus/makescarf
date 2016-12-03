<?php
/**
 * This initialized object helps to access a $_SESSION variable little easily. 
 * @author Shell
 * @version 0.0.1
 * */
namespace Pure;
class Session extends \PureLib\ArrayStorage {
	public function __construct($data = null, $args = null) {
		if(!session_id()) $this->_start();
		$data = $_SESSION;
		$this->_storage = $data;
	}

	public function set($var, $data = null) {
		parent::set($var, $data);
		$_SESSION[$var] = $data;
	}

	protected function _start() {
		session_start();
	}
	
	public static function instance() {
		static $instance;
		if(!is_object($instance)) {
			$instance = new self();
		}
		return $instance;
	}	
}
