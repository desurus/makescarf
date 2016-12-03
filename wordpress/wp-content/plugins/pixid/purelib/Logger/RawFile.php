<?php

namespace PureLib\Logger;
class RawFile extends LoggerAbstract {
	protected $_file;
	protected $_handle;
	public function __construct($file, $args = array()) {
		$defaults = array( 
			'prepend_date' => true,
			'named' => '',
			'new_lines' => true,
			'can_throw' => false
		);
		$args = new \PureLib\Config\RawArray($args, $defaults);
		$this->_args = $args;
		$this->_file = $file;
	}
	protected function _get_date_part() {
		$date = date('[Y-m-d H:i:s]');
		return $date;
	}

	public function can_write() {
		
	}

	protected function _is_opened() {
		if(false === $this->_handle) {
			//
		}
		return is_resource($this->_handle);
	}

	protected function _open() {
		$this->_handle = @fopen($this->_file, 'a');
		return $this;	
	}

	public function log($string) {
		$this->_open();
		if(!is_resource($this->_handle)) return $this;
		if(!is_string($string)) return $this;
		$prepend = "";
		if($this->args()->get('prepend_date', false)) {
			$prepend = $this->_get_date_part();
		}
		$named = $this->args()->get('named');
		if(!empty($named)) {
			$prepend .= " ({$named})";
		}
		$prepend .= ": ";
		$string = $prepend . $string;
		if($this->args()->get('new_lines')) $string .= "\n";

		if(!$this->_is_opened()) {
			$this->_open();
		}
		fwrite($this->_handle, $string);
	}

	public function __destruct() {
		if(is_resource($this->_handle)) {
			fclose($this->_handle);
		}
	}
}
