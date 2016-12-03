<?php

namespace Pure\Module\Debug;
class Module extends \Pure\Module {
	protected $_logger;	

	protected function _init() {
		
		$this->_setup_logger_container();
		$this->_setup_error_handler();	
		$this->_plugin->autoloader()->add_handler(array($this, 'autoloader_handler'));
	}

	public function autoloader_handler(\PureLib\Autoloader $autoloader, $classname) {
		//var_dump($classname)	;
	}

	protected function _setup_error_handler() {
		$settings = $this->settings();
		if($settings->get('track_errors', false)) {
			set_error_handler(array($this, 'error_handler'));
		}	
	}
	/***/
	public function log_pm_exception(\Exception $e) {
		//DO something

	}

	public function error_handler($errno, $errstr, $errfile, $errline, $context) {
		$html = "<pre>\n";
		$html .= "Error ({$errno}): ({$errfile}:{$errline})\n";	
		$html .= "{$errstr}\n";
//		$html .= "File: {$errfile}:{$errline}\n";
		$html .= "</pre>";
		if($this->settings()->get('display_errors', false))
			echo $html;
		if($this->settings()->get('log_errors', false)) {
			//TODO: Log an errors!
			$message = strip_tags($html);		
			$this->logger()->get('error')->log($message);
		}
	}

	public function logger() {
		if(!is_object($this->_logger)) $this->_setup_logger_container();
		return $this->_logger;
	}
	protected function _setup_logger_container() {
		$this->_logger = new \PureLib\Logger\Container();
		$logs_directory = __DIR__ . '/logs/';
		$today = date('d-m-Y');
		$logs_directory .= $today . '/';
		if(!is_dir($logs_directory))
			@mkdir($logs_directory);
		//FIXME: This logs need to be configured via config file.	
		$this->_logger->set('debug', new \PureLib\Logger\RawFile($logs_directory . 'debug.log', array( 'named' => 'PureManager' )))
			->set('error', new \PureLib\Logger\RawFile($logs_directory . 'error.log', array( 'named' => 'PureManager' )));

		\PureLib\Registry::set('logger', $this->_logger);
		return $this->_logger;
	}
	public function __destruct() {	
		$mem_usage = memory_get_usage();
		$c = 0;
		while($mem_usage > 1024) {
			$mem_usage = $mem_usage / 1024;
			$c++;
		}
		$mem_usage = number_format($mem_usage, 2, '.', '.');
		$names = array(
			0 => 'Bytes',
			1 => 'Kilobytes',
			2 => 'Megabytes',
			3 => 'Gigabytes'
		);	
		$name = $names[$c];

		$request_url = $_SERVER["REQUEST_URI"];
		$method = $_SERVER["REQUEST_METHOD"];
		$this
			->logger()
			->get('debug')	
			->log("{$method} {$request_url}. Page loaded. Memory allocated: {$mem_usage} {$name}.");
	}	
}
