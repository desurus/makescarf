<?php
namespace Pure\Widget\Settings;
/**
 * TODO: Maybe we can to get an array of settings just in a little bit easiest way. Something like a special HTTP Query to a current page with some query argument which can "say" to dump an array of settings for all widgets in page?
 * */
class CodeFinder {
	protected $_widget_name;
	protected $_filepath;
	protected $_fileline;
	protected $_call_settings;
	
	protected $_file_content;
	protected $_code_tokens;
	protected $_widget_calls;
	protected $_widget_call_args;
	protected $_this_widget_call;
	public function __construct($widget_name, $filepath, $fileline, $call_settings) {
		$this->set_widget_name($widget_name)	
			->set_filepath($filepath)
			->set_fileline($fileline)
			->set_call_settings($call_settings);
	}

	public function set_widget_name($widget_name) {
		$this->_widget_name = $widget_name;
		return $this;
	}

	public function get_widget_name() {
		return $this->_widget_name;
	}
	public function get_filepath() {
		return $this->_filepath;
	}
	public function set_filepath($filepath) {
		$this->_filepath = $filepath;
		return $this;
	}
	public function get_fileline() {
		return $this->_fileline;
	}
	public function set_fileline($fileline) {
		$this->_fileline = $fileline;
		return $this;
	}
	public function set_call_settings($call_settings) {
		$this->_call_settings = $call_settings;
		return $this;
	}
	public function get_call_settings() {
		return $this->_call_settings;
	}
	public function get_file_content() {
		if(empty($this->_file_content)) {
			$content = file_get_contents($this->get_filepath());
			$this->set_file_content($content);
		}
		return $this->_file_content;
	}
	public function set_file_content($file_content) {
		$this->_file_content = $file_content;
		return $this;
	}

	public function get_code_tokens() {
		if(empty($this->_code_tokens)) {
			$content = $this->get_file_content();
			$tokens = token_get_all($content);
			$this->set_code_tokens($tokens);
		}
		return $this->_code_tokens;
	}
	public function set_code_tokens($tokens) {
		$this->_code_tokens = $tokens;
		return $this;
	}

	protected function _parse() {
		if(null !== $this->_widget_calls) return $this;
		$code_tokens = $this->get_code_tokens();	
		$in_code = $widget_call_started = $widget_args_started = false;	
		$current_code = "";
		$code_blocks = array();
		$widget_name = "";
		$widget_calls = array();
		$widget_args_code = "";
		$direct_widget_call = "";
		$direct_widget_calls_blocks = array();
		$array_nw_decl_started = false;
		$i = $j = -1;
		foreach($code_tokens as $token_num => $code_token) {	
			$code = @$code_token[0];
			$content = @$code_token[1];
			//Seems line?
			$line = @$code_token[2];
			$token_name = "";
		        $next_code_token = $code_tokens[$token_num++];	
			if(is_numeric($code)) {
				$token_name = token_name($code);
			}
			
			
			if(is_string($code_token))	 {
				if($in_code) {
					$current_code .= $code_token;
				}
				//continue;
			}
			if(is_array($code_token) && 
				\PureLib\CodeParser::T_STRING == $code_token[0] &&
				$code_token[1] == 'PM' //This code can get more checks!!!!
			) {
				$widget_call_started = true;
				if($widget_call_line <= 0)
					$widget_call_line = $line;
				$j++;
			}

			if($widget_call_started) {
				if($code_token[0] == \PureLib\CodeParser::T_CONSTANT_ENCAPSED_STRING && empty($widget_name)) {
					//Seems this token is a widget name!
					$widget_name = trim($content, "\"'");	
					//continue;
				}
				if(is_string($code_token)) {
					if($code_token == ';')	{
						$direct_widget_calls_blocks[$j] = $direct_widget_call .= ";";
						//var_dump($widget_args_code);		
						$widget_calls[] = array(
							'widget_name' => $widget_name,
							'code_iterator_num' => $i,
							'widget_call_file' => $this->get_filepath(),
							'widget_call_line' => $widget_call_line,
							'widget_args_code' => $widget_args_code,
							'direct_widget_call' => $direct_widget_call
						);
						$direct_widget_call = "";
						$widget_name = "";
						$widget_call_started = false;
						$widget_call_line = -1;
						$widget_args_started = false;
						$widget_args_code = "";
					}
				}
				//Detect a widget args		
				if(
					(@$code_token[0] == \PureLib\CodeParser::T_ARRAY || @$code_token == "[") 
					
					&& !$widget_args_started) {

						$widget_args_started = true;
						if(@$code_token == "[")
							$array_nw_decl_started = true;
					//$widget_args_code = $content;
				}
				$_code = "";
				if(is_string($code_token)) $_code = $code_token;
				else $_code = $content;
				if($widget_call_started)
					$direct_widget_call .= $_code . "";
				if($widget_args_started) {
					//We need to skip latest ")" in this part of code...
					

					if($_code == ')') {
						$next_token_num = $token_num + 1;
						$next_token = $code_tokens[$next_token_num];
						if($next_token == ';')  { 
							$_code = '';	
						}
					}
					//Detect a new way arrays declarations:
					if($_code == ')' && $array_nw_decl_started) {
						//$prev_token_num = $token_num - 1;
						$array_nw_decl_started = false;
						$_code = '';
					}
					$widget_args_code .= $_code;	
				}
				//$direct_widget_calls_blocks[$j] = $direct_widget_call;
			}


			
			if($code == T_OPEN_TAG) {
				$i++;
				$current_code = $content;
				$in_code = true;
				continue;
			}
			if($code == T_CLOSE_TAG) {
				$current_code .= $content;
				$code_blocks[$i] = $current_code;
				$current_code = "";
				$in_code = false;
				continue;
			}
			if($in_code) {
				$current_code .= $content;
			}
		}	
		if(!empty($widget_calls)) {
			foreach($widget_calls as $k => $widget_call) {
				$widget_call['raw_call_code'] = $code_blocks[$widget_call['code_iterator_num']];
				if(!empty($widget_call['widget_args_code'])) {
					//$widget_call['widget_call_args'] = self::get_widget_call_args($widget_call);
				}
				$widget_calls[$k] = $widget_call;
			} 
		}
		$this->_widget_calls = $widget_calls;
		$widget_calls = $this->_widget_calls;	
		foreach($widget_calls as $widget_call) {
			self::is_same_widget_settings($this->get_call_settings(), $widget_call['widget_args']);
			if(
				\Pure\Helper\Widget::is_same_widget_class($this->get_widget_name(), $widget_call['widget_name']) && 
				self::is_same_widget_settings($this->get_call_settings(), $widget_call['widget_call_args'])	
			) {
				$widget_call = new \Pure\Widget\Call($widget_call);	
				$this->_widget_call_args = $widget_call->get_widget_call_args();	
				$this->_this_widget_call = $widget_call;	
				return $this->_widget_call_args;
			}
		}
		
		return $this;	
	}

	/*
	 * @return \Pure\Widget\Call 
	 * **/
	public function get_widget_call() {
		$this->_parse();
		return $this->_this_widget_call;
	}
	public function get_settings_from_code() {
		//if(null !== $this->_widget_call_args) return $this->_widget_call_args;
		$this->_parse();		
		return $this->_widget_call_args;
	}


	
	public static function is_same_widget_settings($settings_f, $settings_s) {
		$diff = array_udiff_uassoc($settings_f, $settings_s, function($a, $b) {
			if($a == $b) return 0;
			return 1;
		}, function($a, $b) {
			if($a == $b) return 0;
			return 1;
		}); 
		if(empty($diff)) return true;
		//Else: We need todo some advanced checks!	
	}
	public static function is_token_widget_start($token) {
		if(!is_array($token) || empty($token)) return false;
		$start_calls = array(
			'display_widget'
		);
		if($token[0] == \PureLib\CodeParser::T_STRING && in_array($token[1], $start_calls)) return true;
		return false;
	}
}
