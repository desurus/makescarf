<?php
/*
 * This is just a simple chelper class for helper functions to work with request datas...
 * @author Shell
 * @version 0.0.21
 * Due from version 0.0.21 A lot of code must be strongly standartized, a lot of method will be deprecated and replaced for use a more strict coding standard.
 * */

namespace Pure;
class Request {
	protected $_request;
	protected $_get;
	protected $_post;
        public function __construct($get = null, $post = null, $request = null) {	
	}
	protected function _cleanup_wordpress_escapes($data) {
		return stripslashes_deep($data);
	}

	public function get_request_get() {
		if(null == $this->_get) {
			$data = $_GET;
			$data = $this->_cleanup_wordpress_escapes($data);
			$this->_get = new \Pure\Request\Request($data);
		}
		return $this->_get;
	}
	public function get($variable = null, $default = null, $decode = false) {
		if(null == $variable) {
			return $this->get_request_get();
		}
		if(!$this->get_request()->exists($variable)) return $default;
		$result = $this->get_request()->get($variable, $default);
		if(true === $decode) {
			$result = \Pure\Helper\Data::decode($result);
		}
		return $result;
	} 
	public function get_request() {
		if(null == $this->_request) $this->_request = new \Pure\Request\Request($_REQUEST);
		return $this->_request;
	}
        /**
	 * This method just return an instance of a Request with set of global $_POST data.
	 * @param boolean $as_array This method can return an array with data instead an Storage object.
         * @return \Pure\Request\Request
	 */
	public function get_request_post($as_array = false) {
		if(!is_object($this->_post)) { 
			$data = $_POST;
			$data = $this->_cleanup_wordpress_escapes($data);
			$this->_post = new \Pure\Request\Post($data);
		}
		if($as_array) {
			return $this->_post->to_array();
		}
            	return $this->_post;
	}
        public function post() {
		return $this->get_request_post();	
	}
	public function get_post() {
		return $this->post();
	}
	/**
	 * This method just checks if current client request is POST.
	 * @from version 0.0.21
	 * @return boolean
	 * */
	public function is_request_post() {
		return ($_SERVER['REQUEST_METHOD'] == 'POST');	
	}
	/*
	 * Method is deprecated from version 0.0.21
	 * @see \Pure\Request::is_request_post instead.
	 * **/
	public function is_post_request() {
		\Pure\Debug::deprecated_method(__METHOD__, __CLASS__ . '::is_request_post()', '0.0.21');
		return $this->is_request_post();
	}

	public static function instance() {
		static $instance;
		if(!is_object($instance)) $instance = new self();
		return $instance;
	}
}
