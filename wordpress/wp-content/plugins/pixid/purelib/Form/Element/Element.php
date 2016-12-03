<?php
/**
 * This abstract Form element class must be extended to describe a new Form element types for PureLib forms in WAMT framework.
 * @author Shell
 * @version 0.0.21
 * Added few important arguments to a constructor, which can be used to manipulate internal working login in elements.
 * Changelog:
 * 0.0.21: 
 * 	Added second argument and support for internal arguments in overall Element instances. It can be passed as a second argument in constructor, and available via ::get_arg() ::set_arg() method of this class.
 * */
namespace PureLib\Form\Element;

abstract class Element {
	protected $_uid;
	protected $_options;
	protected $_args;
	/*
	 * Just an abstract constructor which can be used for overall Elements in form.
	 * Please, use abstract private method ::_init to write your own initialization code for elements, instead of extending the constructor.
	 * @param array $options This array will be fully used to describe the element properties and maybe some other stuff, which is available in output HTML of this element. Please do not use this array for passing arguments to control element login
	 * @param array $args This array can be used to pass some variables which can be used to control some internal element logic.
	 * **/	
	public function __construct($options = array(), $args = array()) {
		$defaults = array(
			'id' => 'element_' . $this->get_uid(),
			'class' => 'form-control',
			'type' => 'text'
		);
		if(!is_array($options)) {
			throw new \Exception(__CLASS__ . "::__construct only receives an valid array as a first param!");
		}
		$options = array_merge($defaults, $options);
		$this->set_options($options);
		$args = array_merge($this->get_default_args(), $args);
		$this->set_args($args);
		$this->_init();
	}

	public function set_args($args = array()) {
		$this->_args = $args;
		return $this;
	}
	public function get_args() {
		return $this->_args;
	}
	public function set_arg($argument, $value = null) {
		$this->_args[$argument] = $value;
		return $this;
	}
	public function get_arg($argument, $default = null) {
		if(!isset($this->_args[$argument])) return $default;
		return $this->_args[$argument];
	}
	public function set_options($options) {
		$this->_options = $options;
		return $this;
	}

	public function append_options($options) {
		$this->_options = array_merge($this->_options, $options);
	}

	public function get_option($option, $default = null) {
		if(!isset($this->_options[$option])) return $default;
		return $this->_options[$option];
	}
	public function set_option($option, $value = '') {
		$this->_options[$option] = $value;
		return $this;
	}
	public function get_uid() {
		if(empty($this->_uid)) {
			$this->_uid = uniqid();
		}
		return $this->_uid;
	}
	protected function _build_attribs_string($append_skip = array()) {
		$skip = array('label');
		$skip = array_merge($skip, $append_skip);
		$attribs = array();
		foreach($this->_options as $option => $value) {
			if(in_array($option, $skip)) continue;
			if(is_array($value)) {
				//FIXME: Something goes wrong!
				continue;
			}
			if($option == "name") {
				if($this->get_option('multiple', false)) {
					if($this->get_arg('auto_multi_name', false)) {
						if(false === strpos($value, '[') && false === strpos($value, ']')) {
							$value .= "[]";
						}
					}
				}
			}
			$attribs[] = "{$option}=\"{$value}\"";
		}
		return implode(' ', $attribs);
	}
	public function render_input() {
		return $this->_render_input();
	}
	protected function _render_input() {
		$attribs = $this->_build_attribs_string();
		return "<input {$attribs}>";
	}
	public function get_id() {
		return $this->get_option('id', 'element_' . $this->get_uid());
	}
	public function render() {
		$html = "<div class=\"form-group\">";
		$id = $this_>get_id();
		$label = $this->get_option('label', '');
		$html .= "<label for=\"{$id}\">{$label}</label>";	
		
		$html .= $this->_render_input();

		$html .= "</div>";	
		return $html;
	}
	public function set_value($value) {
		return $this->set_option('value', $value);
	}
	public function get_value() {
		return $this->get_option('value');
	}
	public function get_name() {
		return $this->get_option('name');
	}
	public function is_valid() {
		//TODO: This method must do checks?
		$required = $this->get_option('required', false);
		if($required) {
			$value = $this->get_value();
			if(empty($value)) return false;
		}	
		return true;
	}
	/**
	 * This method can be easily overrided in child class. It can be used while initialization to passing some default args...
	 * @return array An array of default arguments which is available in this element.
	 * */
	public function get_default_args() {
		return array(
			'auto_multi_name' => true
		);	
	}
}
