<?php

namespace Pure\Widget\Menu\Items;

class Widget extends \Pure\Widget {
	protected function _get_target_menu() {
		$menu = $this->args()->get('menu', false);
		if(false == $menu) {
			//Get a first menu from a theme location instead if no menu slug ID or something else provided.
			$theme_location = $this->args()->get('theme_location', false);
			if(false == $theme_location) {
				//oops, TODO: We need to show some info, seems we can not get a valid menu object, if no valid data provided
				//return $this->error_box("You must provide a some datasource for this widget. Please, use 'menu' => ?, or 'theme_location' => ? data arguments for this widget.");
				return;
			}
			$locations = get_nav_menu_locations();
			if(!empty($locations[$theme_location])) {
				$menu = $locations[$theme_location];
			}
		}
		return $menu;
	}

	public function widget() {
		$menu = $this->_get_target_menu();

		if(false == $menu) {
			return $this->error_box("You must provide a some datasource for this widget. Please, use 'menu' => ?, or 'theme_location' => ? data arguments for this widget.");
		}
		$menu_items = array();
		$menu_classes = array();
		$menu_classes_string = "";
		if(false != $menu) {
			$menu_items = wp_get_nav_menu_items($menu, array());
			_wp_menu_item_classes_by_context($menu_items);
			$menu_classes = $this->args()->get('menu_class', '');
			if($this->args()->get('menu_classes')) {
				$menu_classes = array_merge($this->args()->get('menu_classes'), $menu_classes);
			}
			if(!is_array($menu_classes)) $menu_classes = explode(' ', $menu_classes);
			$menu_classes_string = implode(' ', $menu_classes);
		}
		$this->display_template(array(
			"menu_items" => $menu_items,
			'menu_classes' => $menu_classes,
			'menu_classes_string' => $menu_classes_string
		));
	}

	public function get_simple_edit_buttons() {
		$menu = $this->_get_target_menu();	
		return array(
			new \Pure\Editor\Button(array(
				'title' => 'Edit menu',
				'link' => \Pure\Helper\Url::customizer('nav_menus'),
				'icon' => 'edit'
			))
		);
	}
}
