<?php

/**
 * For this widget we use a pretty functional google maps plugin `wp-google-maps`
 * https://ru.wordpress.org/plugins/wp-google-maps
 * */

namespace Pure\Widget\Maps\Google\Simple;

class Widget extends \Pure\Widget {
	public function widget() {
		$map_id = $this->args()->get('map_id');
		if(empty($map_id)) {
			//TODO: Error?
		}
		echo do_shortcode("[wpgmza id=\"{$map_id}\"]");
	}

	public function get_simple_edit_buttons() {
		$map_id = $this->args()->get('map_id', 0);
		return array(
			new \Pure\Editor\Button(array(
				'icon' => 'edit',
				'title' => 'Edit this map',
				'link' => admin_url("admin.php?page=wp-google-maps-menu&action=edit&map_id={$map_id}")
			))
		);
	}
}
