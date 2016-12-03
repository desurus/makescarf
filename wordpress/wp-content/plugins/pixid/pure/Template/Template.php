<?php

namespace Pure\Template;

class Template {
	protected $_widget_classname;
	protected $_template_directory_base;
	protected $_template_directory_base_url;
	protected $_name;
	
	protected $_theme;

	public function __construct($widget_classname, $template_directory_base) {
		$clear = array(
			'//' => '/'
		);
		$template_directory_base = str_replace(array_keys($clear), array_values($clear), $template_directory_base);
		$this->_widget_classname = $widget_classname;
		$this->_template_directory_base = $template_directory_base;
		$this->_template_directory_base_url = $this->get_template_base_url();
	}
	
	public function get_name() {
		if(empty($this->_name)) {
			$_ = explode('/', $this->_template_directory_base);
			$this->_name = $_[count($_)-1];
		}
		return $this->_name;
	}

	public function get_title() {
		//TODO: 
		return $this->get_name();
	}

	public function get_main_template_file() {
		return $this->_template_directory_base . '/template.php';
	}

	public function get_custom_path($file) {
		return $this->_template_directory_base . '/' . $file;
	}

	public function get_dependencies_file() {
		return $this->_template_directory_base . '/dependency.php';
	}
	
	public function get_template_directory() {
		return $this->_template_directory_base;
	}
	public function get_template_directory_uri() {
		return $this->_template_directory_base_url;
	}
	public function get_template_base_url() {
		//Uhhh
		//FIXME: This this method can not work properly on all server configurations	
		$replaces = array(
			ABSPATH => home_url('/')
		);	
		$url = str_replace(array_keys($replaces), array_values($replaces), $this->_template_directory_base);	
		return $url;
	}
	
	public function exists() {
		return is_dir($this->get_template_directory());
	}

	public function file_exists($relative_path) {
		$root = $this->get_template_directory();
		$root = trailingslashit($root);
		return file_exists($root . $relative_path);
	}
	public function get_images_dir_path() {
		return $this->_template_directory_base . '/images';
	}
	public function get_images_dir_url() {
		return $this->_template_directory_base_url . '/images';
	}
	/**
	 * This method return an a wordpress theme object, which we used in this template
	 * */
	public function theme() {
		if(!is_object($this->_theme)) {
			$this->_theme = wp_get_theme();
		}
		return $this->_theme;
	}
	/*
	 * This method return a theme name where this template is located. If this is not a internal template of the widget.
	 * FIXME: At this moment this method is useless, because it's based on current ACTIVE wordpress theme. This is not a valid.
	 * */
	public function get_theme_name() {
		$wp_theme = $this->theme();
		return $wp_theme->Name;	
	}
	/**
	 * This method return an template relative path from it's root folder, the root folder can be a theme directory, or a widgets registered path.
	 * */
	public function get_template_rel_path() {
		//FIXME: need to get a relative path just from widgets directory, not only theme path
		$full_path = $this->get_template_directory();
		$theme_path = $this->theme()->get_template_directory();
		return str_replace($theme_path, '', $full_path);	
	}
}
