<?php
/**
 * Basic autoloader for this project.
 * @version 0.2
 * @author Shell
 * */
namespace PureLib;

require_once __DIR__ . '/Exception.php';
require_once __DIR__ . '/Registry.php';
class Autoloader {
	protected $_namespaces;
	protected $_handlers;

	public function __construct() {
		spl_autoload_register(array( $this, 'autoload' ), false);
		\PureLib\Registry::set('autoloader', $this);
	}

	public function add_handler($handler) {
		if(!is_callable($handler)) {
			throw new \Exception("Can not add invalid handler. Handler must be a valid callback.");
		}
		$this->_handlers[] = $handler;
	}
	public function autoload($classname) {
		$full_path = $this->get_filename_by_class($classname);
		if(file_exists($full_path)) {
			return include $full_path;	
		}	
	}
	/**
	 * This method registers PureLib namespace based on current Autoloader.php file location.
	 * @param string $directory A custom path to Purelib directory. Default it's null and current directory will be used.
	 * @return \PureLib\Autoloader 
	 * */
	public function register($directory = null) {
		if(null == $directory) {
			$directory = __DIR__;
		}
		$this->register_namespace('PureLib', $directory);
		return $this;
	}
	public function register_namespace($namespace, $root) {
		if(empty($namespace) || !is_string($namespace)) throw new Exception("Invalid namespace.");
		if(!is_array($root)) $root = array( $root );
		if(!isset($this->_namespaces[$namespace])) $this->_namespaces[$namespace] = array();	
		foreach($root as $path) {
			$path = self::trailingslashit($path);
			$this->_namespaces[$namespace][] = $path;
		}
		return $this;
	}
	
	public function get_filename_by_class($classname) {
		if(empty($classname) || !is_string($classname)) throw new \Exception("Class name must be a valid not empty string!");
		foreach($this->_namespaces as $namespace => $include_pathes) {
			foreach($include_pathes as $include_path) {
				if(false !== strpos($classname, "{$namespace}\\")) {
					$file = str_replace('\\', DIRECTORY_SEPARATOR, $classname);
					$full_path = str_replace("{$namespace}\\", $include_path, $classname) . ".php";
					$full_path = str_replace("\\", DIRECTORY_SEPARATOR, $full_path);
					return $full_path;
				}
			}
		}
		if(!empty($this->_handlers))	{
			foreach($this->_handlers as $handler) {
				$result = call_user_func($handler, $this, $classname);
				if(null !== $result) {
					if(!empty($result)) return $result;
				}
			}
		}
		return false;
	}
	public static function trailingslashit($path) {
		$path = rtrim($path, '/');
		$path .= "/";
		return $path;
	}
}

