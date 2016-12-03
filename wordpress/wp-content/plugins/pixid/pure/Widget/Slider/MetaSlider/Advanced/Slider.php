<?php
/**
 * This class required for backcompatibility. 
 * @*/
namespace Pure\Widget\Slider\MetaSlider\Advanced;

class Slider extends \Pure\Widget {
	public function widget() {
		$slider = new \MetaSlider($this->args()->get('slider_id'), array());	
		$this->display_template(array('slider' => $slider));	
	}
	public function get_simple_edit_buttons() {
		$slider_id = $this->args()->get('slider_id');
		return array(
			array(
				'icon' => 'edit',
				'title' => 'Edit this slider',
				'link' => admin_url("admin.php?page=metaslider&id={$slider_id}")
			)
		);
	}
}
