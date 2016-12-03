<?php

namespace Pure;
class DataDirectory {
	protected $_root;
	public function __construct($root) {
		$this->set_root($root);
	}
	public function set_root($root) {
		$this->_root = $root;
		return $this;
	}
	public function get_root() {
		return $this->_root;
	}

	public function get_path($name) {
		$root = $this->get_root();
		$path = trailingslashit($root) . $name;
		return $path;
	}
	
	public function exists($name = null) {
		if(null == $name) $path = $this->get_root();
		else {
			$path = $this->get_path($name);
		}
		return is_readable($path);
	}
	public function maybe_create_directory($name) {
		$path = $this->get_path($name);
		if(!is_dir($path)) {
			@mkdir($path);
		}
		return $this;
	}
}
