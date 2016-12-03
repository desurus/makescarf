<?php
namespace PureLib\Logger;

class Container {
	protected $_logs_directory_root;
	protected $_loggers;
	
	public function __construct($args = array()) {
		$defaults = array(
			'logs_directory_root' => __DIR__ . '/logs/'
		);
		$args = array_merge($defaults, $args);
		$this->_logs_directory_root = $args['logs_directory_root'];
		
		//if(!is_dir($this->_logs_directory_root)) throw new \Exception("Can not find the root directory `{$this->_logs_directory_root}` for logs. Please check it.");
		//if(!is_writable($this->_logs_directory_root)) throw new \Exception("Seems your root logs directory is not writable.");
	}

	public function get($instance_name = 'debug') {
		if(empty($this->_loggers[$instance_name])) {
			$this->_loggers[$instance_name]	 = new Hole(); 
		}
		return $this->_loggers[$instance_name];
	}

	public function set($instance_name, LoggerAbstract $instance) {
		$this->_loggers[$instance_name] = $instance;
		return $this;
	}
}
