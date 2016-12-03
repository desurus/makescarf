<?php

/*
 * This widget helps to display and control the 
 * This widget requires a full plugin "Contact Form 7". Which is available at https://wordpress.org/plugins/contact-form-7/
 * **/

namespace Pure\Widget\CForm7;

class CForm7 extends \Pure\Widget {
	
	public function widget() {
		$form_id = $this->args()->get('form');
		$html_id = $this->args()->get('html_id', '');
		$html_class = $this->args()->get('html_class', '');
		if(empty($form)) {
			//TODO:
		}	
		echo do_shortcode("[contact-form-7 id=\"{$form_id}\" html_id=\"{$html_id}\" html_class=\"{$html_class}\"]");
	}
	
	public function get_simple_edit_buttons() {
		return array(
			array(
				'icon' => 'edit',
				'title' => 'Edit contact form',
				'link' => 'TODO:'
			)
		);
	}
}
