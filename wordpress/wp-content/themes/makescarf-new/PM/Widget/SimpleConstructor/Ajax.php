<?php

namespace MakeScarf\Widget\SimpleConstructor;
class Ajax extends \Pure\Module\Ajax\Handler {
	protected function _create_scarf_hash($scarf_data, $user_ID) {
		$keys = array(
			'layout',
			'color',
			'fontsize',
			'font',
			'action',
			'body'
		);
		$data = array('user_ID' => $user_ID);
		foreach($keys as $key) {
			$data[$key] = $scarf_data[$key];
		}
		$hash = md5(implode('#', $data));
		return $hash;
	}
	public function save_scarf_session_action() {
		$constructor = $this->request()->get('constructor');
		$constructor['body'] = $this->request()->get('scarf_body_html');
		$this->Session()
			->set('constructor', $constructor);
		$response = array('status' => 0);
		echo json_encode($response);	
	}
	public function save_scarf_cookie_action() {
		$constructor = $this->request()->get('constructor');
		$cookie_data = serialize($constructor);
		$cookie_data = base64_encode($cookie_data);
		setcookie("makescarf_constructor", $cookie_data, 0, "/", $_SERVER['HTTP_HOST']);
		$response = array('status' => 0, 'message' => "Scarf saved in cookie!");
		echo json_encode($response);
		exit();	
	}
	public function make_product_title($constructor) {
		$user = PM()->User()
			->get_current_user();
		//$color_name = $this->widget()->get_color_nicename($constructor['color']);
		$color_name = $constructor['background_color'];
		$title = "Scarf color: {$color_name}, style: {$constructor['style']}, User: {$user->user_email}"; 
		return $title;
	}
	public function send_form_action() {
		$constructor = $this->Session()->get('constructor');	
		$constructor = $this->request()->get('constructor');
		$constructor['body'] = $constructor['content'];
			
		$this->widget()->save_in_cookie($constructor);

		if(!is_user_logged_in()) {
			$html = $this
				->widget()
				->fetch_template('login.php');

			$response = new \Pure\Response\JSON(0, "Registration required", array("html" => $html));
			$response->send();
		} else {	
			$user = PM()->User()
				->get_current_user();
			
			if(!empty($constructor['product_id'])) {
				$product = PM()->Woo()
						->get_product_by_id($constructor['product_id']);
				
				if(!$product) {
					$constructor['product_id'] = '';
				} else {
					if($product->post->post_author != $user->ID) {
						//Hackers? :D
						$response = new \Pure\Response\JSON(1, "Access denied! You can not manipulate with this product ID.", array()); 
						$response->send();
					}
				}
			}
			//Try to get existing product from hash	
			$post_title = $this->make_product_title($constructor);

			$attributes = array(	
				'background_color' => $constructor['background_color'],
				'font' => $constructor['font'],
				'style' => $constructor['style'],
				'font_color' => $constructor['font_color'],
				'artwork_approval' => $constructor['artwork_approval']
			);
			if(empty($attributes['font_color'])) {
				$attributes['font_color'] = '000000';
			}
			$metas = array(
				'other_instructions' => $constructor['other_instructions']
			);


			$product = array(
				'post_author' => PM()->User()->get_current_user_ID(),
				'post_status' => 'private',
				'post_content' => $constructor['content'],
				'post_title' => $post_title,
				'attributes' => $attributes,
				'regular_price' => $this->widget()->args()->get('default_price')
			);
			if(!empty($constructor['product_id'])) {
				$product['ID'] = intval($constructor['product_id']);
			}
			
			$product_ID = PM()->Woo()
				->save_product($product);
			if(!empty($metas)) {
				foreach($metas as $meta => $value) {
					update_post_meta($product_ID, $meta, $value);
				}
			}

			$this->Session()
				->set('current_scarf_id', $product_ID);

			if($constructor['action'] == 'add_to_cart') {
				//TODO: Advanced adding this scarf to cart!
				wp_update_post(array(
					'ID' => $product_ID,
					'post_status' => 'publish'
				));	

				PM()->Woo()
					->add_to_cart($product_ID);

				setcookie("makescarf_constructor", "", 0, "/", $_SERVER['HTTP_HOST']);
				$html = $this->widget()->fetch_template('added_to_cart.php');
				$response = new \Pure\Response\JSON(0, "Product added to cart.", array('html' => $html));
				$response->send();	
			} else {
				$account_url = "/account/";
				$html = "<p>Scarf saved in your <a href=\"{$account_url}\">account page</a>.";
				$response = new \Pure\Response\JSON(0, "Scarf saved.", array("html" => $html));
				$response->send();	
			}	
			//PM()->module("Iframe")->footer();	
		}
		exit();
	}	
	public function print_preview_action() {
		PM()->display_widget("\MakeScarf\Widget\ScarfPreview\Widget", array("template" => "fullpage_preview", "post_id" => $this->widget()->args()->get('scarf_id')));
		exit();
	}	
} 
