<?php
/**
 * This is a base class for abstract models.
 * Classes which has a lot of private properties which can be setuped automaticaly by providing an array of properties.
 * And gets an access to it's properties by override __call __get methods, instead of direct access to a properties.
 * Any of child classes can just override their getters and setters directly.
 * @author Shell
 * @version 0.1
 * @
 * */
namespace PureLib;

abstract class Model {
	public function __construct($data = array()) {
		$this->set_data($data);	
	}

	public function __call($method, $args) {
		$property_name = substr_replace($method, '', 0, 4);
		$property_name = self::sanitize_property_name($property_name);
		
		if(substr($method, 0, 4) == 'get_') {	
			if(property_exists($this, $property_name)) {
				return $this->{$property_name};
			} else {
				throw new \Exception("Call to undefined method ``{$method}`` in class " . get_class($this));
			}
		}
		if(substr($method, 0, 4) == 'set_') {
			if(property_exists($this, $property_name)) {
				$this->{$property_name} = $args[0];
				return $this;
			} else {
				throw new \Exception("Call to undefined method ``{$method}`` in class " . get_class($this));
			}
		}
		throw new \Exception("Call to undefined method ``{$method}`` in class " . get_class($this));
	}

	public function set_data($data = array()) {
		if(!is_array($data))	return $this;
		if(empty($data)) return $this;
		foreach($data as $property => $value) {
			$property_name = self::sanitize_property_name($property);
			if(property_exists($this, $property_name)) {
				$setter = self::get_setter_name($property_name);
				if(method_exists($this, $setter)) {
					$this->{$method}($value);
				} else {
					$this->{$property_name} = $value;
				}
			}
		}
		return $this;
	}

	public static function get_setter_name($property) {
		$property = ltrim($property);
		$method = "set_{$property}";
		return $method;
	}

	public static function sanitize_property_name($property) {
		$property = ltrim($property, '_');
		$property = "_{$property}";
		return $property;
	}
}
