<?php
namespace PureLib;

class View {
	protected $_registered_helpers;
	//protected $_loaded_helpers;

	protected $_templates_base;
	protected $_vars;
	protected $_suppress_warnings = false;

	public function __construct($templates_base) {
		$this->set_templates_base($templates_base);
		$this->_vars = array();
	}

	public function suppress_warnings($suppress = null) {
		if(null === $suppress) return $this->_suppress_warnings;
		$this->_suppress_warnings = $suppress;
	}
	public function set_templates_base($directory) {
		$templates_base = $directory;
		if(empty($templates_base) || !is_string($templates_base)) throw new \Exception("Can not set an empty templates directory for view object.");
		if(!is_readable($templates_base) || !is_dir($templates_base)) throw new \Exception("The base templates directory does not exists, ot not readable.");
		$this->_templates_base = $templates_base;
	}

	public function get_templates_base() {
		return $this->_templates_base;
	}

	public function assign($var, $data = null) {
		$this->_vars[$var] = $data;
	}
	
	public function get_vars() {
		return $this->_vars;
	}
	public function fetch($__file, $__vars = array()) {
		if(!file_exists($this->get_templates_base() . '/' . $__file)) {
			throw new \Exception("Can not locate view file {$__file} in path {$this->get_templates_base()}/{$__file}");
		}
		if($this->suppress_warnings()) {
			$old_err_reporting = error_reporting();
			error_reporting(E_ERROR);
		}
		ob_start();
		if(null === $__vars) {
			$__vars = array();
		}
		if(!is_array($__vars)) {
			throw new \Exception(__CLASS__, __METHOD__, __FILE__, __LINE__, "Second argument must be a valid array. Allowed empty or null.");
		}	
		$__vars = array_merge($this->get_vars(), $__vars);
		extract($__vars, EXTR_SKIP);
		include $this->get_templates_base() . '/' . $__file;
		if($this->suppress_warnings()) {
			error_reporting($old_err_reporting);
		}
		return ob_get_clean();
	}

	public function display($file, $vars = array()) {
		echo $this->fetch($file, $vars);
	}

	public function render($file, $vars = array()) {
		return $this->display($file, $vars);
	}

	public function __call($method, $args) {
		if(method_exists($this, $method)) {
			return call_user_func_array(array($this, $method), $args);
		}
		if(( $helper = $this->find_helper($method)))	return $helper;
		throw new \Exception("Call to undefined method ``{$method}``. Seems helper with same name not found in registered helpers pathes.");
	}

	public function find_helper($name) {
		if(empty($this->_registered_helpers[$name])) return false;
		return $this->_registered_helpers[$name];
	}
	public function register_helper($name, $helper) {
		if(!($helper instanceof \PureLib\View\Helper)) throw new \Exception("Helper must be a valid instance of \PureLib\View\Helper.");
		$helper->set_view($this);
		$helper->set_name($name);
		$this->_registered_helpers[$name] = $helper;
	}
}
