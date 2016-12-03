<?php

/*
 * This widget helps to show and control the sliders on wordpress website.
 * This widget is a basic which just call the backend plugin methods and functions. Without any modifications...
 * Please, use @look:\Pure\Widget\Slider\MetaSlider\Advanced\Slider to have a more configuration just from PureManager plugin, without backend.
 * Widget uses the plugin ml-slider. Which availbale here: https://wordpress.org/plugins/ml-slider/
 * **/

namespace Pure\Widget\Slider\MetaSlider\Direct;

class Slider extends \Pure\Widget {
	public function widget() {
		$slider_id = $this->args()->get('slider_id');
		echo do_shortcode("[metaslider id={$slider_id}]");
	}
	
	public function get_simple_edit_buttons() {
		$slider_id = $this->args()->get('slider_id');
		return array(
			array(
				'icon' => 'edit',
				'title' => 'Edit slider',
				'link' => admin_url("admin.php?page=metaslider&id={$slider_id}")
			)
		);
	}	
}
