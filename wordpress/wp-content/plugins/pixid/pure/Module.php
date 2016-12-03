<?php
/**
 * The basic class which must be extended by any module.
 * This class contains basic methods available for all modules.
 * IMPORTANT NOTES:
 * 1. We can not use a global PM() function to get instance of the global plugin variable, because a lot of modules can be initialized in plugin constructor.
 * */
namespace Pure;
abstract class Module {
	protected $_plugin;
	protected $_settings;
	protected $_title;
	/**
	 * The basic module constructor, it gets an PM() instance and a settings array.
	 * We need a stored instance for access other stuff in PM().
	 * */
	public function __construct($plugin = false, $settings = null) {
		$this->_plugin = $plugin;
		$this->set_settings($settings);
		$this->_init();
	}
	public function set_settings($settings) {
		if(!is_object($settings)) $settings = new \PureLib\Config\RawArray($settings);
		$this->_settings = $settings;
	}

	public function settings() {
		return $this->_settings;	
	}

	public function get_module_name() {
		return \Pure\Helper\Utils::base_classname(get_class($this));	
	}

	public function get_title() {
		return $this->_title;
	}
	public function set_title($title) {
		$this->_title = $title;
		return $this;
	}
	/**
	 * This method returns just an initialized instance of \Pure\Request 
	 * @return \Pure\Request
	 * */
	public function request() {
		return \Pure\Request::instance();		
	}
	/** 
	 * This is a temporary method to display module service pages contents 
	 * */
	public function dispatch($view_file, $assign = array()) {	
		$assign['module'] = $this;
		
		$content = $this->view()->fetch($view_file, $assign);
		$header = $this->view()->fetch("iframe-header.php", array("title" => $this->get_title()));
		$footer = $this->view()->fetch("iframe-footer.php", array());
		echo $header . $content . $footer;
	}
	/**
	 * This method helps to easy close the Modules modal windows...
	 * */
	public function close_modal($refresh_parent = false, $exit = true) {
		$this->dispatch("common/modal_close.php", array("refresh_parent" => $refresh_parent));
		if($exit) exit();
		return true;
	}
	/**
	 * This is a temporary solution, seems we need to initialize a different view object for Modules
	 * or just refactor the global view object initialization and usage!
	 * @return \PureLib\View
	 * */
	public function view() {
		return PM()->view();
	}
	/**
	 * This method just shows an error in HTML
	 * */
	public function error_box($error) {
		return PM()->error_box($error);	
	}
	/** 
	 * This method executes just at a plugin load! Not at any wordpress hook. 
	 * Important: We must not do a complex tasks in _init methods, because it executes on each wordpress core is loaded!
	 *
	 * */
	abstract protected function _init();
}
