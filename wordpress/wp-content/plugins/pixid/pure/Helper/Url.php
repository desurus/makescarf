<?php

namespace Pure\Helper; 
class Url extends \PureLib\Helper\Url {
	public function __construct() {
		
	}
	public static function path_to_public_uri($path) {
		$replaces = array(
			ABSPATH => home_url('/')
		);	
		$url = str_replace(array_keys($replaces), array_values($replaces), $path);	
		return $url;
	}

	public static function customizer($panel = '', $return_url = null) {
		$base_url = admin_url('customize.php');
		$autofocus = array(
			'panel' => $panel
		);
		if(null == $return_url) {
			$return_url = $_SERVER['REQUEST_URI'];
		}
		$url = add_query_arg(array('autofocus' => $autofocus, 'return_url' => $return_url), $base_url);
		return $url;
	}

	public static function instance() {
		static $instance;
		if(!is_object($instance)) {
			$instance = new self();
		}
		return $instance;
	}

	public function widget_ajax($widget, $action, $params = array()) {
		$base_url = admin_url('admin-ajax.php');
		$params = array_merge(array(
			'action' => 'pm_widget_ajax',
			'pm_action' => $action,
			'widget' => Data::encode($widget) 
		), $params);
		$url = add_query_arg($params, $base_url);

		return $url;
	}
}
