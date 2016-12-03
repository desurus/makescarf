<?php

namespace Pure\Widget;
class Call extends \PureLib\Model {
	protected $_widget_name;
	protected $_widget_call_file;
	protected $_widget_call_line;
	protected $_widget_args_code;
	protected $_raw_call_code;
	protected $_widget_call_args;
	protected $_direct_widget_call;
	public function __construct($widget_call_data = array()) {
		if(!is_array($widget_call_data) || empty($widget_call_data)) {
			//throw new \Pure\Exception("You must provide a valid array with widget call data.");
		}
		parent::__construct($widget_call_data);
	}
	public function get_widget_call_args() {
		if($this->_widget_call_args === NULL) {
			if(!empty($this->_widget_args_code))	{
				$code = "return " . $this->_widget_args_code . ';';
				ob_start();
				$result = eval($code);
				$output = ob_get_clean();
				if(!empty($output)) {
					//Maybe we can just setup an errors to this object?
					echo "<pre>" . $output . "</pre>";
				}
				$this->_widget_call_args = $result;
				if(null === $result) $this->_widget_call_args = null;
			} else {
				$this->_widget_call_args = false;
			}	
		}
		return $this->_widget_call_args;
	}
	public function set_widget_call_args($args) {
		$this->_widget_call_args = $args;
		return $this;
	}
	public function build_direct_widget_call($rebuild = false) {
		if(!empty($this->_direct_widget_call) && false == $rebuild) return $this;
	        	
		$widget_name = $this->get_widget_name();
		if(empty($widget_name) || !is_string($widget_name)) throw new \Pure\Exception("You must set a valid Widget classname to build a call code!");
		$settings_array_declaration = "array()";
		$settings_call_args = $this->get_widget_call_args();
		$parts = array();
		if(!empty($settings_call_args))	 {
			$settings_array_declaration = "array( \n";
			foreach($settings_call_args as $name => $value) {
				$parts[] = self::build_settings_array_part($name, $value);			
			}
			$parts = self::glue_array_parts($parts); 
			$settings_array_declaration .= "{$parts} \n)";
		}
		$call_code = "PM()->display_widget(\"{$widget_name}\", {$settings_array_declaration}";
		$call_code .= ");";

		$this->_direct_widget_call = $call_code;
		$this->_raw_call_code = "<?php {$this->_direct_widget_call} ?>";
		return $this;
	}

	public static function build_settings_array_part($arg_name, $arg_value) {
		$code = "\"{$arg_name}\" => ";
		if(is_string($arg_value)) {
			$code .= "\"{$arg_value}\"";
		}
		if(is_object($arg_value)) {
			//At this moment we does not support this features...
			return null;
		}
		if(is_array($arg_value)) {
			$code .= " array( \n";
			foreach($arg_value as $arg => $arg_v) {
				$parts[] = self::build_settings_array_part($arg, $arg_v);
			}
			$parts = self::glue_array_parts($parts);
			$code .= "{$parts} \n)";
		}
		return $code;
	}

	public static function glue_array_parts($parts = array()) {
		if(!is_array($parts) || empty($parts)) return false;
		foreach($parts as $k => $v) {
			if($v === null)
				unset($parts[$k]);
		}
		return implode(",\n", $parts);
	}

	public function __sleep() {
		$vars = array_keys(get_object_vars($this));
		unset($this->_widget_call_args['call_from']);
		//unset($this->_widget_call_args['call_from']['object']);
		return $vars;
	}
}
