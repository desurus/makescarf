<?php
namespace Pure\Widget\Login;

class Form extends \Pure\Widget {
	public function widget($args = []) {
		if(is_user_logged_in()) {
			
		} else {
			$this->display_template();
		}	
	}
}
