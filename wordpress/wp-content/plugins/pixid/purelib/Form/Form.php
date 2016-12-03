<?php
/**
 * This is a base class for all forms in this application.
 * If you try to extend this class for a new form class, please note a few moments:
 * All fields initializations must be done only in your class::init() method, not in class::__construct()
 * Dropped the previous Bootstrap form decorators
 * Changelog:
 * From version 0.21 Form implements the ArrayAccess object. Now we can easily access the form elements like in a simple array.
 * @version 0.21
 * @author Shell
 * */
namespace PureLib\Form;

class Form implements \ArrayAccess { 
	
	protected $_messages;
	protected $_elements;
	protected $_view;
	protected $_uid;
	protected $_options;

	protected $_args;
	/*
	 * @TODO: Need to add a simple doc block to this method, with description of available options and arguments.
	 * @param array $options The options array will contain an array of form properties and/or attributes which can be used directly in HTML output
	 * @param array $args This array can be used to provide some argument variables which can control some form display or working logic.
	 * **/
	public function __construct($options = array(), $args = array()) {
		$uid = uniqid();
		$this->_uid = $uid;
		$defaults = array(
			'name' => 'form_' . $uid,
			'method' => 'post',
			'action' => '',
			'id' => 'form_' . $uid
		);	
		$options = array_merge($defaults, $options);
		$this->_options = $options;
		$default_args = array(
			'auto_submit' => true
		);
		$args = array_merge($default_args, $args);
		$this->_args = $args;
		$this->_init();
	}
	public function get_uid() {
		if(empty($this->_uid)) $this->_uid = uniqid();
		return $this->_uid;
	}
	public function get_id() {
		return $this->get_option('id', 'form_' . $this->get_uid());
	}
	protected function _init() {
	
	}
	
	public function get_elements() {
		return $this->_elements;
	}	
	public function add_message($message) {
		
		$this->_messages[] = $message;
	}
	

	

	public function get_data() {
		return $this->getValues();
	}
		
	public function get_option($option, $default = null) {
		if(!isset($this->_options[$option])) return $default;
		return $this->_options[$option];
	}
	public function set_option($option, $value = '') {
		$this->_options[$option] = $value;
	}

	public function render_header() {
		$html = "";
		$html .= "<form ";
		$options = array();
		foreach($this->_options as $option => $value) {
			$options[] = "{$option}=\"{$value}\"";
		}
		$html .= implode(' ', $options);
		$html .= ">\n";
		return $html;
	}
	public function render_footer() {
		$html = "";
		$html .= "</form>";
		return $html;
	}
	public function render() {
		$html = ""; 
		$html .= $this->render_header();	
		if(!empty($this->_elements)) {
			$submit_exists = false;
			foreach($this->_elements as $element) {
				if($element->get_option('type') == 'submit') $submit_exists = true;
			}
			if(!$submit_exists && $this->_args['auto_submit']) {
				$this->add_element('submit', 'save', array(
					'label' => 'Save'
				));	
			}
			foreach($this->_elements as $element) {
				$html .= $element->render();
			}
		} else {
		
		}
		$html .= $this->render_footer();	
		return $html;
	}

	public function isValid() {
		return $this->is_valid();
	}
	
	/*
	 * Set a from elements with values from provided array
	 * @param array $values
	 * @return boolean 
	 * **/	
	public function set_values($values) {
		if(empty($values)) {
			return false;
		}
		if(!is_array($values)) throw new \Exception("You must provide a valid array.");
		foreach($values as $name => $value) {
			try { 
				$element = $this->get_element($name);
				$element->set_value($value);
			} catch(\Exception $e) {
				//Just not found this element here. This is not a critical error I think
			}
		}
		return $this;
	}
	/**
	 * Set a dedicated value to element with name.
	 * @param string $element The element name which value will be set.
	 * @param mixed $value The mixed new value for this element.
	 * */
	public function set_value($element, $value) {
		$element = $this->get_element($element);
		$element->set_value($value);
		return $this;
	}
	/*
	 * Gets a form element by it's name.
	 * Form must be initialized, and element exists.
	 * @param string $element_name The name of required element.
	 * @return \Panel\Form\Element\Element
	 * **/
	public function get_element($element_name) {
		if(empty($element_name) || !is_string($element_name)) throw new \Exception("You must provide a valid non empty string.");
		foreach($this->_elements as $element) {
			if($element->get_option('name') == $element_name) {
				return $element;
			} 
		}
		throw new \Exception(sprintf("Can not find an element with name %s", htmlspecialchars($element_name)));
	}
	/**
	 * This method pushes new element to form.
	 * @param mixed $element. Element type name or instance of \Panel\Form\Element\Element class.
	 * @param string $name. Optional element name. You must provide it, if $element not an initialized object.
	 * @param array $options. An array of other options to this element. @see \Panel\Form\Element\Element::__construct(); 
	 * @return \Panel\Form\Form
	 * */
	public function add_element($element, $name = "", $options = array()) {
		if(($element instanceof Element\Element)) {
			$this->_elements[] = $element;
			return $this;
		}
		$element_class_name = __NAMESPACE__ . "\\Element\\" . ucfirst($element);
		if(!class_exists($element_class_name, true)) {
			throw new \Exception("Can not find a valid class for element: " . htmlspecialchars($element));
		}
		$element = new $element_class_name();
		if(!empty($options)) {
			$element->append_options($options);
		}
		if(!empty($name)) {
			$element->set_option('name', $name);
		}	
		
		$this->_elements[] = $element;
		return $this;
	}
	/*
	 * This method just searches a submit element in current form.
	 * **/
	protected function _get_submit_element() {
		if(empty($this->_elements)) return false;
		foreach($this->_elements as $element) {
			if($element->get_option('type') == 'submit') return $element;
		}
		return false;
	}
	

	public function getValues() {
		return $this->get_values();
	}
	/**
	 * This method gets all field values.
	 * TODO: Maybe we need to call a some clear filters here?
	 * */
	public function get_values() {
		$elements = $this->_elements;
		$values = array();
		foreach($elements as $element) {
			$values[ $element->get_name() ] = $element->get_value();	
		}
		return $values;
	}

	public function __toString() {
		return $this->render();
	}
	/**
	 * Method to describe the \ArrayAccess class.
	 * */
	public function offsetExists($offset) {
		try {
			$element = $this->get_element($offset);
			return true;
		} catch(\Exception $e) {
			return false;
		}
	}	
	public function offsetGet($offset) {
		try {
			$element = $this->get_element($offset);
			return $element;
		} catch(\Exception $e) {
			return null;
		}
	}
	public function offsetSet($offset, $value) {
		try {
			$this->set_value($offset, $value);
			return $this;
		} catch(\Exception $e) {
			return $this;
		}
	}
	/**
	 * Fixme: We need to implement this method fully. */
	public function offsetUnset($offset) {
		foreach($this->_elements as $name => $element) {
			//TODO:
		}
	}
}
