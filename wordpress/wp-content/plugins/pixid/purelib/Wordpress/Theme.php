<?php
/**
 * This is a main singleton class for new wordpress themes developed with WAMT Framework usage.
 * This class just helps to do a really small but routine tasks
 * TODO: Automatically include few files in template/css and template/js directories!
 * */
namespace PureLib\Wordpress;

abstract class Theme extends WithHooks {
	protected $_args;
	protected $_view;
	public function get_view() {
		if(!is_object($this->_view)) {
			$dir = get_template_directory();
			$this->_view = new \PureLib\View($dir . "/views/");
		}
		return $this->_view;
	}
}
