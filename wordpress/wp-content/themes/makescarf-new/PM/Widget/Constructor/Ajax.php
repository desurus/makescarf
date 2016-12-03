<?php

namespace MakeScarf\Widget\Constructor;
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
		$constructor['body'] = $this->request()->get('scarf_body_html');	
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
		$color_name = $constructor['color'];
		$title = "Scarf color: {$color_name}, layout: {$constructor['layout']}, style: {$constructor['style']}, User: {$user->user_email}"; 
		return $title;
	}
	public function send_form_action() {
		$constructor = $this->Session()->get('constructor');	
		$constructor = $this->request()->get('constructor');
		$constructor['body'] = $this->request()->get('scarf_body_html');
		//\Pure\Helper\Session::store('constructor_data', $constructor);
		
		if(!is_user_logged_in()) {
			//echo PM()->module("Iframe")->header(array('auto_resize' => true));
			$this
				->widget()
				->display_template('login.php');
			//echo PM()->module("Iframe")->footer();
		} else {
			//Save scarf!
			//PM()->module("Iframe")->header();
			$user = PM()->User()
				->get_current_user();
			
			if(!empty($constructor['product_id'])) {
				$product = PM()->Woo()
						->get_product_by_id($constructor['product_id']);
				/*if(!$product) {
					$response = array("status" => 1, "html" => "<p>Sorry, the saved scarf</p>")
				}*/
				if(!$product) {
					$constructor['product_id'] = '';
				} else {
					if($product->post->post_author != $user->ID) {
						//Hackers? :D
						$response = array("status" => 1, "html" => "<p>You can not edit this scarf.</p>", 'product_id' => 0);
						echo json_encode($response);
						exit();
					}
				}
			}
			//Try to get existing product from hash	
			$post_title = $this->make_product_title($constructor);
			$attributes = array(
				'layout' => $constructor['layout'],
				'color' => $this->widget()->get_real_color_value($constructor['color']),
				'fontsize' => $constructor['fontsize'],
				'font' => $constructor['font'],
				'style' => $constructor['style']
			);


			$product = array(
				'post_author' => $user->ID,
				'post_status' => 'private',
				'post_content' => $constructor['body'],
				'post_title' => $post_title,
				'attributes' => $attributes,
				'regular_price' => $this->widget()->args()->get('default_price')
			);
			if(!empty($constructor['product_id'])) {
				$product['ID'] = intval($constructor['product_id']);
			}
			
			$product_ID = PM()->Woo()
				->save_product($product);
			$this->Session()
				->set('current_scarf_id', $product_ID);
			if($this->request()->get('form_action', 'save') == 'add_to_cart') {
				//TODO: Advanced adding this scarf to cart!
				wp_update_post(array(
					'ID' => $product_ID,
					'post_status' => 'publish'
				));	
				PM()->Woo()
					->add_to_cart($product_ID);
				setcookie("makescarf_constructor", "", 0, "/", $_SERVER['HTTP_HOST']);
				$response = array('status' => 0, 'html' => $this->widget()->fetch_template('added_to_cart.php'), 'product_id' => $product_ID);
				echo json_encode($response);
			        exit();	
			} else {
				$account_url = "/account/";
				$response = array('status' => 0, 'html' => "<p>Scarf saved in your <a href=\"{$account_url}\">account page</a>.", 'product_id' => $product_ID);
				echo json_encode($response);
				exit();
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
