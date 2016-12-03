<?php

namespace MakeScarf\Widget\Settings;
class Widget extends \Pure\Widget\Internal {
	public function widget() {
		$settings = get_option('makescarf_settings', array(
			'default_price' => '68',
			'sizes' => array(
				'width' => '65in',
				'height' => '27in'
			),
			'indent' => '225px'
		));
		if($this->request()->is_post_request()) {
			$settings = $this->request()->get_request_post()->get('makescarf');
			update_option('makescarf_settings', $settings);
		}
		$this->display_template(compact('settings'));	
	}
}
