<?php

namespace Pure\Settings\Container;

use \Pure\Settings as Settings;

class WithTemplate extends \Pure\Settings\Container {
	public function __construct($widget = null) {
		$widget_classname = $widget;
		if(is_object($widget)) $widget_classname = get_class($widget);

		$widget_templates = (new \Pure\Template\Finder($widget_classname))->find_all();	
		$values = array();
		foreach($widget_templates as $template) {
			if($template->exists()) {
				$theme_name = $template->get_theme_name();
				$values[$template->get_name()] = $template->get_title() . " ()";
			}
		}
			
		$this->add( new Settings\Element\TemplateSelect(array(
			'name' => 'template',
			'id' => 'template',
			'title' => __('Template', WAMT_DOMAIN),
			'description' => __('You can select individual template for this widget', WAMT_DOMAIN),
			'values' => $values,
			'default_value' => 'default'
		)) );
		parent::__construct($widget);
	}
	protected function _init() {
		
	}	
}
