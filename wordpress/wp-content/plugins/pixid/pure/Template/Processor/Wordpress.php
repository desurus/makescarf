<?php

namespace Pure\Template\Processor;

class Wordpress {
	
	protected static $_working_directory = null;

	protected $_template;
	protected $_original_code;
	protected $_code_crc;
	protected $_processed_filename;
	protected $_output_filepath;
	protected $_theme_name;
	protected $_template_filename;
	protected $_result_code;
	public function __construct($template) {
		if(!file_exists($template)) {
			throw new \Exception("The template file {$template} can not be located. Terminating!");
		}
		if(null == self::get_working_directory()) {
			throw new \Exception("You must set the working directory with " . __CLASS__ . "::set_working_directory(\$directory) before usign this class as object.");
		}
		$this->set_template($template);
	}

	
	public function set_template($template) {
		$this->_template = $template;
		return $this;
	}


	public function get_original_code() {
		if(empty($this->_original_code)) {
			$this->_original_code = file_get_contents($this->get_template());
		}
		return $this->_original_code;
	}

	public function get_code_crc() {
		if(empty($this->_code_crc)) {
			$this->_code_crc = md5($this->get_original_code());
		}
		return $this->_code_crc;
	}

	public function get_processed_filename() {
		if(empty($this->_processed_filename)) {
			$code_crc = $this->get_code_crc();
			$this->_processed_filename = "{$code_crc}.php";	
		}
		return $this->_processed_filename;
	}

	public function get_theme_name() {
		if(empty($this->_theme_name)) {
			$this->_theme_name = basename(dirname($this->get_template()));
		}	
		return $this->_theme_name;
	}

	public function get_template_filename() {
		if(empty($this->_template_filename)) {
			$this->_template_filename = basename($this->get_template());
		}
		return $this->_template_filename;
	}
	public function get_output_filename() {
		return $this->get_code_crc() . ".php";
	}
	public function get_output_filepath() {
		if(empty($this->_output_filepath)) {
			$theme_name = $this->get_theme_name();
			$template_filename = $this->get_template_filename();
			$working_dir = trailingslashit(self::get_working_directory());
			$output_filename = $this->get_output_filename();
			$this->_output_filepath = "{$working_dir}{$theme_name}/{$template_filename}/{$output_filename}";
		}		
		return $this->_output_filepath;
	}

	protected function _maybe_create_output_dir() {
		$theme_name = $this->get_theme_name();
		$template_filename = $this->get_template_filename();
		$base_dir = self::get_working_directory();
		$theme_dir = "{$base_dir}/{$theme_name}";
		if(!is_dir($theme_dir)) {
			mkdir($theme_dir);	
		}
		$template_dir = "{$theme_dir}/{$template_filename}";
		if(!is_dir($template_dir)) {
			mkdir($template_dir);
		}
		return true;
	}

	public function is_cached() {
		$output_filepath = $this->get_output_filepath();
		return file_exists($output_filepath);	
	}

	public function process_from_original() {
		$code_tokens = token_get_all($this->get_original_code());
		$in_code = false;
		$current_code = $result = "";	
		foreach($code_tokens as $token) {
			if(is_string($token)) {
				if($in_code) {
					$current_code .= $token;
				} else {
					$result .= $token;
				}
			}
			$code = $token[0];
			$content = @$token[1];	
			if($code == 308) {
				if($content == 'get_header') {
					$content = $this->get_early_header_call();
				}
				if($content == 'get_footer') {
					$content = $this->get_early_footer_call();
				}
			}
			$result .= $content;	
		}
		$this->_result_code = $result;
		return $this->_result_code;	
	}
	public function process($ignore_cache = false) {
		$this->_maybe_create_output_dir();
		$output_filepath = $this->get_output_filepath();
		if($ignore_cache || false == $this->is_cached()) {
			$result_code = $this->process_from_original();	
			file_put_contents($output_filepath, $result_code);
			return $this;
		} else {	
			return $this;
		}	
	}

	public function get_template() {
		return $this->_template;
	}

	public function get_early_header_call() {
		return 'PM()->template_processor()->early_header';
	}

	public function get_early_footer_call() {
		return 'PM()->template_processor()->early_footer';
	}

	
	public static function set_working_directory($directory) {
		if(!is_dir($directory) || !is_writable($directory)) {
			throw new \Exception("Sorry but working directory for this class must exists and be writable by current user.");
		}
		self::$_working_directory = $directory;
	}
	public static function get_working_directory() {
		return self::$_working_directory;
	}
}
