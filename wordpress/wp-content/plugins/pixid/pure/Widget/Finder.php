<?php
/*
 * This is not a widget, this class provide a flexible functions to manipulate and search a widgets. 
 * **/
namespace Pure\Widget;
class Finder {
	protected $_widget_classname;
	protected $_filepath;
	protected $_ajax_handler_classname;

	public function __construct($classname) {
		$this->_widget_classname = $classname;	
	}
	
	public function get_widget_classname()	 {
		return $this->_widget_classname;
	}
	public function get_ajax_handler_classname() {
		if(empty($this->_ajax_handler_classname)) {
			$widget_classname = $this->get_widget_classname();
			$_ = explode('\\', $widget_classname);
			$_[count($_)-1] = 'Ajax';
			$this->_ajax_handler_classname = implode('\\', $_);
		}
		return $this->_ajax_handler_classname;
	}
	public function get_namespace() {
		$_ = explode('\\', $this->_widget_classname);
		unset($_[count($_) - 1]);
		return implode('\\', $_);
	}	

	public function get_file_path() {
		if(!empty($this->_filepath)) return $this->_filepath;

		$autoloader = \PureLib\Registry::get('autoloader');
		$widget_file = $autoloader->get_filename_by_class($this->_widget_classname);
		$this->_filepath = $widget_file;
		return $this->_filepath;
	}
	
	public function get_directory() {
		return $this->get_directory_path();
	}
	public function get_directory_path() {
		return dirname($this->get_file_path());
	}

	public function get_directory_uri() {
		$directory = $this->get_directory_path();
		return \Pure\Helper\Url::path_to_public_uri($directory);
	} 
}
