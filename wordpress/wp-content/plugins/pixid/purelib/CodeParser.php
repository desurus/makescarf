<?php

namespace PureLib;
/*
 * TODO: Need to write a code to get all PHP inserts in content!
 * **/
class CodeParser {
	const T_STRING = 308; //Also for function calls()
	const T_CONSTANT_ENCAPSED_STRING = 316;//Seems a strings in ""
	const T_ARRAY	= 364;//T_ARRAY array declaration?

	protected $_original_content;
	protected $_content;
	protected $_parsed;
	
	public function __construct($content) {
		if(!is_string($content)) throw new \Exception("You must provide a valid string!");
		$this->_original_content = $content;
	}
	
	public function parse() {
		if(empty($this->_parsed)) {
			$this->_parsed = token_get_all($this->_original_content);
		}
		return $this;
	}

	public function encode_code($code) {
		return base64_encode($code);
	}
	public function decode_code($code) {
		return base64_decode($code);
	}
	public function get_code_element($code) {	
		$encoded_code = $this->encode_code($code);
		$element = "[pmcode lang=php]{$encoded_code}[/pmcode]";
		return $element;
	}

	public function to_visual() {
		$this->parse();
		if(empty($this->_parsed)) {
			throw new \Exception("Seems we can not parse your source string, the result parsed array is empty...");
		}
		$result = "";
		$in_code = false;
		$current_code = "";
		foreach($this->_parsed as $token) {
			if(is_string($token)) {
				if($in_code) {
					$current_code .= $token;
				} else {
					$result .= $token;
				}
			}
			$code = $token[0];
			$content = @$token[1];	
			if($code == T_OPEN_TAG || $code == T_OPEN_TAG_WITH_ECHO) {
				$in_code = true;	
			} elseif($code == T_CLOSE_TAG) {
				$in_code = false;
				$current_code .= $content;
				//Seems we need to move this code...
				$result .= $this->get_code_element($current_code);
				$current_code = "";
				continue;
			}
			if($in_code) {
				$current_code .= $content;
			} else {
				$result .= $content;
			}
		}
		$this->_content = $result;
		return $result;
	}
       	
		
}
