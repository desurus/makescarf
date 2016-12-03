<?php
/**
 * This module provides a overall structore and ajax functionality to a Pure widgets...
 * @version 0.1
 * @author Shell
 * */
namespace Pure\Module\Ajax;
class Module extends \Pure\Module {
	protected function _init() {
		add_action('wp_ajax_pm_widget_ajax', array($this, 'pm_widget_ajax_handler'));
		add_action('wp_ajax_nopriv_pm_widget_ajax', array($this, 'pm_widget_ajax_handler'));	
	}		
	public function pm_widget_ajax_handler() {
		
		$action = $this
				->request()
				->get('pm_action', false);
		$widget = $this
				->request()
				->get('widget', false, true);
		$wid = $this
				->request()
				->get('wid', false);
		if(empty($wid)) {
			//TODO: show an error, because any ajax widget actions must store their call data!
		}
		
		$call = \Pure\Widget::find_call_in_session($wid);
		if(empty($call) || !is_object($call) || !($call instanceof \Pure\Widget\Call)) {
			$error = sprintf(__('Sorry, seems your session was expired, please, refresh a webpage and try again!'));
			$this->error_box($error);	
			exit();
		}	
		if(empty($action) || empty($widget)) {
			//TODO: Trigger an json error here!	
		}	
		if(!\Pure\Helper\Widget::is_valid($widget)) {
			//TODO: Trigger an error, because this widget is not registered, or !not registered yet!
		}
		$finder = new \Pure\Widget\Finder($widget);
		$directory = $finder->get_directory();
		if(file_exists($directory . '/Ajax.php')) {
			$ajax_handler = $finder->get_ajax_handler_classname();
			$ajax_handler = new $ajax_handler(new $widget($call->get_widget_call_args()));
			$method = "{$action}_action";
			if(method_exists($ajax_handler, $method))	{
				$ajax_handler->$method();
				exit();
			}
		}	
	}
}
