<?php
namespace MakeScarf\Widget\SimpleConstructor;
class Widget extends \Pure\Widget {
	protected function _init() {
		if(!$this->args()->get('default_price')) {
			$default_price = get_option('default_price');
			$this->args()->set('default_price', $default_price);	
		}
		if(!$this->args()->get('default_biege_code')) {
			$this->args()->set('default_biege_code', 'E0D9CC');
		}
	}
	public function widget() {
		add_filter('mce_buttons', function($buttons) {
			$buttons = array(
				'bold', 
				'italic', 
				'underline', 
				'strikethrough',
				'|',
				'alignleft',
				'aligncenter',
				'alignjustify',
				'alignright'
			);	
			return $buttons;
		});
		$fonts = array(
			'ZapfinoForteL' => array(
				'title' => 'Hand Writing',
				'style' => "color: #010101; font-family: 'ZapfinoForteL'; font-size: 32px;",
				'font_family' => 'ZapfinoForteL'
			),
			'Century Gothic' => array(
				'title' => 'Modern',
				'style' => "color: #676666; font-family: 'Century Gothic'; font-size: 22px; font-weight: 400;",
				'font_family' => 'Century Gothic'
			),
			'MyUnderwood' => array(
				'title' => 'Book Typewriting',
				'style' => "color: #676666; font-family: 'MyUnderwood'; font-size: 18px;",
				'font_family' => 'MyUnderwood'
			)
		);

		add_filter('mce_buttons_2', function($buttons) {
			$buttons = array();	
			return $buttons;
		});
		$content = "Our distinctive scarves offer unlimited ways to thank clients, reward employees and even advertise your company in unique ways. Through our convenient customization process and dedicated customer service, you can arrange for large orders and enjoy a first-class process from start-to-finish. Our distinctive scarves offer unlimited ways to thank clients, reward employees and even advertise your company in unique ways. Through our convenient customization process and dedicated customer service, you can arrange for large orders and enjoy a first-class process from start-to-finish. Our distinctive scarves offer unlimited ways to thank clients, reward employees and even advertise your company in unique ways. Through our convenient customization process and dedicated customer service, you can arrange for large orders and enjoy a first-class process from start-to-finish.";
		$selected_posts = $this->get_request()->get('select_posts', array());
		if(!empty($selected_posts) && is_array($selected_posts)) {
			$content = "";
			foreach($selected_posts as $post_id) {
				$post = get_post($post_id);
				if($post->post_type != 'post' || $post->post_status != 'publish') continue;
					$content .= $post->post_content;
			}	
		}
		$scarf_data = array(
			'content' => $content,
			'background_color' => 'F5F1DE' //Biege default color code
		);
		//Maybe load from cookie	
		$cookie_data = $_COOKIE['makescarf_constructor'];
		if(!empty($cookie_data)) {
			$_data = @unserialize(base64_decode($cookie_data));
			if(!empty($_data)) {
				foreach($_data as $key => $value) {
					//FIXME: Need some cleaning?	
				}
			}
			$scarf_data = $_data;	
		}

		$this->display_template(compact('scarf_data', 'fonts')); 
	}
	public function save_in_cookie($constructor) {
		$cookie_data = serialize($constructor);
		$cookie_data = base64_encode($cookie_data);
		setcookie("makescarf_constructor", $cookie_data, 0, "/", $_SERVER['HTTP_HOST']);
	}	
}
