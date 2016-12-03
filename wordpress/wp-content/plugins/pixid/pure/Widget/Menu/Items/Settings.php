<?php

namespace Pure\Widget\Menu\Items;

use \Pure\Settings\Element as Element;

class Settings extends \Pure\Settings\Container\WithTemplate {
	protected function _init() {
		$menu_locations = \Pure\Helper\Wordpress::get_menu_locations();
		if(empty($menu_locations)) {
			
		}
		$values = array();
		foreach($menu_locations as $location => $title) {
			$value = $location;
			$title = "({$location}) {$title}";
			$values[$value] = $title;
		}
		$this->add(new Element\Dropdown(array(
			'values' => $values,
			'value' => -1,
			'title' => __('Menu theme location', WAMT_DOMAIN),
			'description' => __('Please, select a preferred menu to display.', WAMT_DOMAIN),
			'name' => 'theme_location'
		)));	
	}
}
