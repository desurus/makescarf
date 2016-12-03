<?php

/*
 * This class contains overall functionality to display, manipulate and work with an HTML elements wrap with editor and advanced functions
 * TODO: ?? Seems this class can be merged with a InlineWrapper module!
 * @version 0.1-dev
 * @author Shell
 * This is a simple devel version, just in pre-dev state.
 * **/

namespace Pure;
class InlineWrapper {
	protected $_global_config;
	
	public function __construct($global_config = array()) {
		$this->_global_config = $global_config;
	}
	
	public function can_include_editor($widget = null, $args = array()) {
		return PM()->can_include_editor($widget, $args);
	}
	public function is_editor_module_enabled() {
		$settings = PM()->module("InlineEditor")->settings();	
		if(!is_object($settings) || !$settings->get('enabled')) return false;
		return true;
	}

	public function maybe_wrap_with_editor($code) {
		if($this->can_include_editor()) return $this->wrap_with_editor($code);
		return $code;
	}
	public function maybe_wrap_with_editor_widget($widget, $args) {		
		if(!($widget instanceof \Pure\Widget)) {
			return $widget->widget($args, $args);	
		}
		ob_start();       
		$widget->widget($args, $args);
		$content = ob_get_clean();
		if(!$this->is_editor_module_enabled()) {
			return $content;	
		}
		if(!$widget->args()->get('wrap_widget', true)) {
			return $content;
		}
		if(!$widget->args()->get('wrap_area', true)) {
			return $content;
		}
		if(!$this->can_include_editor($widget, $args)) return $content;
			
		//At this moment widgets can have a simple callback to get a very basic help to edit some content...
		$internal_buttons = $widget->get_internal_buttons();
		
		if(!is_array($internal_buttons)) $internal_buttons = array();
		$widget_edit_buttons = $widget->get_simple_edit_buttons();
		if(!is_array($widget_edit_buttons)) $widget_edit_buttons = array();
		$append_edit_buttons = $widget->args()->get('append_edit_buttons', array());
		if(!is_array($append_edit_buttons)) $append_edit_buttons = array();

		$edit_buttons = array_merge($internal_buttons, $widget_edit_buttons, $append_edit_buttons);

		$code = $content;
		$uid = uniqid();
		$content = PM()->view()->fetch('inline_wrapper_widget.php', compact('uid', 'args', 'code', 'edit_buttons', 'widget'));	
		return $content;
	}
	public function display_widget($widget_name, $args = array()) {	
		if(!class_exists($widget_name, true)) {
			return PM()->error_box("Sorry, but widget {$widget_name} not is valid widget, and not available in registered widgets pathes.");
		}
		$widget = new $widget_name($args, $args);
		echo $this->maybe_wrap_with_editor_widget($widget, $args);
	}

	public function wrap_with_editor($code, $args = array()) {
		//create unique id?
		$default_args = array(
			'snippet_path' => '',
			'wrapper_style' => ''
		);
		$args = wp_parse_args($args, $default_args);
		//extract($args, EXTR_SKIP);
		$file = '';
		if(!empty($args['snippet_path'])) {
			$file = $args['snippet_path']; 
		}	
		$uid = uniqid();
		$edit_buttons = array(
			new \Pure\Editor\Button(array(
				'icon' => 'edit',
				'title' => 'Edit this snippet',
				'link' => \Pure\Helper\Url::ajax('pm_snippet_edit', array('file' => \Pure\Helper\Data::encode($file))),
				'action_url' => \Pure\Helper\Url::ajax('pm_snippet_edit', array('file' => \Pure\Helper\Data::encode($file)))
			))
		);
		ob_start();
		PM()->view()->display('inline_wrapper.php', compact('uid', 'args', 'file', 'code', 'edit_buttons'));
		$html = ob_get_clean();
		return $html;
	}	
}
