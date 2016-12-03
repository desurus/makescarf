<?php

namespace PureLib\FS\Node;
abstract class Node {
	protected $_handle = null;
	protected $_fullpath;
	public function __construct($fullpath) {
		$this->set_fullpath($fullpath);
	}
	protected function _build_path($append_path) {
		return trailingslashit(trailingslashit($this->get_fullpath()) . $append_path);
	}
	public function get_basename() {
		return basename($this->get_fullpath());
	}
	public function set_fullpath($fullpath) {
		$this->_fullpath = $fullpath;
	}
	public function get_fullpath() {
		return $this->_fullpath;
	}
	public function get_handle() {
		return $this->_handle;
	}
	public function exists($path = null) {
		if(null == $path)
			return file_exists($this->get_fullpath());
		$path = $this->_build_path($path);
		return file_exists($path);
	}
	public function is_readable() {
		return is_readable($this->get_fullpath());
	}
	public function is_writable() {
		return is_writable($this->get_fullpath());
	}

	public function chmod($mode) {
		return chmod($this->get_fullpath(), $mode);
	}
	public function is_dir($path = null) {
		if(null == $path) return is_dir($this->get_fullpath());
		$path = $this->_build_path($path);
		return is_dir($path);
	}
	/*
	 * This method tries to get a public URL to this file node, if it's located in a $_SERVER['DOCUMENT_ROOT'] directory.
	 * **/
	public function get_public_uri() {
		$document_root = @$_SERVER['DOCUMENT_ROOT'];
		if(empty($document_root)) {
			//This is not possible, if no DOCUMENT_ROOT available	
			return false;
		}
		if(false === strpos($this->get_fullpath(), $document_root)) {
			//This node not in document root
			return false;
		}
		$result = str_replace($document_root, '/', $this->get_fullpath());
		return '/' . ltrim($result, '/');
	}

	/**
	 * FIXME: Need to use not a WP function trailingslashit...
	 * */
	public static function trailingslashit($path) {
		return trailingslashit($path);
	}
}
