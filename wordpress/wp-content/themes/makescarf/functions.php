<?php
include dirname(__FILE__).'/include/tdwt.class.php';
include dirname(__FILE__).'/include/tdwt.options.class.php';
define('LIBRARY_TAX_ID', 3);
define('MAKE_SCARF_PAGE_ID', 31);
define('CART_PAGE_ID', 17);
define('ORDER_PAGE_ID', 692);
define('ERROR_CODE_EMAIL_IN_USE', 1);
define('USE_DISCOUNT_COUPONS', true);
define('DISCOUNT_COOKIE_LIFETIME', 60*60*24*7);

ini_set('display_errors', 'On');
error_reporting(E_ALL);
class MakeScarf_Theme extends TDWT_Class {
	public function Init() {
		add_action('wp_enqueue_scripts', array($this, 'RegisterScripts'));
		add_action('init', array($this, 'WpInit'));
		add_action('admin_menu', array($this, 'RegisterAdminMenu'));
		add_shortcode('makescarf_page', array($this, 'MakeScarfPage'));
		add_shortcode('orderscarf_page', array($this, 'MakeOrderPage'));
		add_shortcode('scarf_cart', array($this, 'ScarfCartPage'));
		add_shortcode('scarf_account_page', array($this, 'ScarfAccountPage'));
		add_shortcode('scarf_preview', array($this, 'ScarfPreview'));
		add_action('wp_ajax_modify_count', array($this, 'AjaxModifyCount'));
		add_action('wp_ajax_nopriv_modify_count', array($this, 'AjaxModifyCount'));
		add_action('wp_ajax_modify_style', array($this, 'AjaxModifyStyle'));
		add_action('wp_ajax_nopriv_modify_style', array($this, 'AjaxModifyStyle'));
		add_action('wp_ajax_remove_scarf', array($this, 'AjaxRemoveScarf'));
		add_action('wp_ajax_nopriv_remove_scarf', array($this, 'AjaxRemoveScarf'));
		add_action('wp_ajax_use_coupon', array($this, 'AjaxUseCoupon'));
		add_action('wp_ajax_nopriv_use_coupon', array($this, 'AjaxUseCoupon'));
		add_action('wp_head', array($this, 'WpHead'));
		add_action('parse_query', array($this, 'WpParseQuery'));
		add_action('add_meta_boxes', array($this, 'RegisterMetabox'));
		add_action('admin_head', array($this, 'AdminHead'));
		add_action('login_head', array($this, 'LoginHead'));
		add_filter('wp_mail_from', array($this, 'MailFrom'));
		$this->options = new TDWT_Options(array(
			'social_html' => array(
				'type' => 'textarea',
				'default' => $this->FetchTemplatePart('top-social.php'),
				'title' => 'Top social html code.',
				'description' => 'Be careful, close all tags or your theme will ugly.'
			),
			'preview_text' => array(
				'type' => 'textarea',
				'default' => $this->FetchTemplatePart('loremipsum.php'),
				'title' => 'Default preview text.'
				),
			'boutique_url' => array(
				'type' => 'text',
				'default' => 'http://www.google.com/',
				'title' => 'Top boutique url'
			),
			'default_price' => array(
				'type' => 'number',
				'default' => 68,
				'title' => 'Default scarf price'	
			),
			'default_delivery_price' => array(
				'type' => 'number',
				'default' => 30,
				'title' => 'Default delivery coast'
			),
			'paypal_account' => array(
				'type' => 'text',
				'default' => 'ayanami.dev@yandex.ua',
				'title' => 'Your paypal account'	
			),
			));
		$this->options->CreateThemeOptionsPage('Settings', 'MakeScarf Settings', 'makescarf-settings');
		register_nav_menu('top-menu', 'Top menu');
		register_nav_menu('bottom', 'Bottom menu');
			
	}
	public function RegisterAdminMenu() {
		add_utility_page('Shipment Settings', 'Shipment Settings', 'administrator', 'shipment-settings', array($this, 'ShipmentSettings'));
	}
	public function ShipmentSettings() {
		$error_msg = false;
		$saved = false;
		if(!is_writable($file = dirname(__FILE__).'/delivery.ini'))
			$error_msg = "Can not edit the settings the file `{$file}` is not writable, this file must be writable by a web server (666 permission mask).";
		if(self::IsPostRequest()) {
			$settings = stripslashes($_POST['settings_content']);
			if(empty($error_msg)) {
				file_put_contents($file, $settings);	
				$saved = true;
			}
		}
		echo $this->FetchTemplatePart('shipment-settings.php', array('settings_content' => file_get_contents(dirname(__FILE__).'/delivery.ini'), 'error_msg' => $error_msg, 'saved' => $saved));
	}
	public function ScarfPreview() {
		
	}
	public function MailFrom() {
		return 'robot@'.$_SERVER['HTTP_HOST'];
	}
	public function AjaxRemoveScarf() {
		//die("1");
		$sid = @intval($_REQUEST['sid']);
		$scarf = get_post($sid);
		$user = wp_get_current_user();
		if($scarf->post_author == $user->IDi || $scarf->post_author == 0) {
			wp_delete_post($scarf->ID);
		}
		die("1");

	}
	public static function AjaxErroResponse($message = "An error occured during ajax request. Please, try later") {
		$response = array(
			'code' => 1,
			'message' => $message
		);
		echo json_encode($response);
		exit();
	}
	public static function GetCouponByCode($code) {
		if(empty($code)) return false;
		$posts = get_posts([
			'post_type' => 'coupon',
			'post_status' => 'publish',
			'posts_per_page' => 1,
			'meta_key' => 'coupon_code',
			'meta_value' => $code
		]);
		if(empty($posts)) return false;
		$coupon = $posts[0];	
		$coupon->discount_percent = get_post_meta($coupon->ID, 'discount_percent', true);
		$coupon->coupon_code = $code;
		return $coupon;
	}
	public static function GetCouponById($id) {
		if(empty($id)) return false;
		$coupon = get_post($id);
		if($coupon->post_type != 'coupon') return false;	
		$coupon->discount_percent = get_post_meta($coupon->ID, 'discount_percent', true);
		$coupon->coupon_code = get_post_meta($coupon->ID, 'coupon_code');
		return $coupon;
	}
	public static function HasSessionCoupon() {
		if(empty($_COOKIE["coupon_cookie"])) return false;
		$coupon_cookie = $_COOKIE["coupon_cookie"];
		return true;
	}
	public static function GetSessionCoupon() {
		@session_start();
		if(empty($_COOKIE["coupon_cookie"])) return false;
		$coupon_code = base64_decode($_COOKIE["coupon_cookie"]);	
		return self::GetCouponByCode($coupon_code);
	}
	public static function SetSessionCoupon($coupon) {
		$current_coupon = self::GetSessionCoupon();
		if(!empty($current_coupon)) {
			//Trigger error?
			//setcookie("coupon_cookie", "", -1);
			//return;
		}
		setcookie("coupon_cookie", base64_encode($coupon->coupon_code), time() + DISCOUNT_COOKIE_LIFETIME, "/");
	}
	public function AjaxUseCoupon() {
		if(empty($_REQUEST['coupon_code'])) {
			return self::AjaxErroResponse();
		}	
		$coupon_code = $_REQUEST['coupon_code'];
		$coupon = self::GetCouponByCode($coupon_code);
		if(empty($coupon)) {
			$response = array(
				'code' => 2,
				'message' => 'We unable to find a coupon with this code...'
			);
			echo json_encode($response);
			exit();
		}
		$response = [
			'code' => 0,
			'message' => 'We found a valid coupon with this code',
			'coupon' => [
				'ID' => $coupon->ID,
				'discount_percent' => $coupon->discount_percent,
				'coupon_code' => $coupon->coupon_code
			]
		];
		self::SetSessionCoupon($coupon);
		echo json_encode($response);
		exit();
	}
	public function LoginHead() {
		echo "<style>#login h1 {display:none;}</style>";
	}
	public function AdminHead() {
		$css_url = get_template_directory_uri().'/admin.css';
		echo "<link rel='stylesheet' href='{$css_url}' />";
	}
	public function RegisterMetabox() {
		add_meta_box('scarvs-metabox', 'Scarvs preview', array($this, 'ScarvsMetabox'), 'orders', 'normal', 'high');
	}
	public function GetOrderScarvs($ID) {
		$scarvs = maybe_unserialize(get_post_meta($ID, 'scarvs', true));
		if(is_array($scarvs)) {
		foreach($scarvs as $key => $scarf) {
			$scarf = get_post($scarf);
			$scarvs[$key] = $this->LoadScarfData($scarf);
		}
		}
		return $scarvs;
	}
	public function ScarvsMetabox() {
		global $post;
		$scarvs = maybe_unserialize(get_post_meta($post->ID, 'scarvs', true));
		foreach($scarvs as $key => $scarf) {
			$scarf = get_post($scarf);
			if(!is_object($scarf))
				continue;
			$scarvs[$key] = $this->LoadScarfData($scarf);
		}
		
		echo $this->FetchTemplatePart('scarvs-admin-preview-small.php', array('scarvs' => $scarvs));
	}
	public function WpParseQuery($query) {
		if(is_admin()) {
			//Hide scarf draft items
			//return;
			//if($query->query_vars['post_type'] == 'cart_items')
			//	$query->query_vars['post_status'] = 'publish';
		}
		if((is_category() && $query->query_vars['category_name'] == 'library') || (is_archive() && $query->query_vars['post_type'] == 'scarf_gallery')) {
			$query->set('posts_per_page', -1);
		}
	}
	public function WpHead() {
		$url = admin_url('admin-ajax.php');
		echo "\n<script type='text/javascript'>var scarf_ajax_url = '{$url}'; </script>\n";
	}
	public function AjaxModifyCount() {
		@session_start();
		$item_id = intval($_REQUEST['item_id']);
		$count = intval($_REQUEST['count']);
		if(false === array_search($item_id, $_SESSION['SCARF_SIMPLE_CART']['items'])) {
			//Hacking attemp?
			die("you are ugly man.");
		}
		//TODO: IF count <= 0 delete item_id
		if($count <= 0) {
			wp_delete_post($item_id);
			$key = array_search($item_id, $_SESSION['SCARF_SIMPLE_CART']['items']);
			unset($_SESSION['SCARF_SIMPLE_CART']['items'][$key]);
			echo json_encode(array('action' => 'remove', 'id' => $item_id, 'total_price' => $this->CalcTotalItemsPrice($this->GetCartItems())));
			die();
		}
		update_post_meta($item_id, 'items_count', $count);
		echo json_encode(array('action' => 'update', 'total_price' => $this->CalcTotalItemsPrice($this->GetCartItems())));
		die();
	}
	public function AjaxModifyStyle() {
		$item_id = @intval($_REQUEST['item_id']);
		if(empty($item_id))
			self::SimpleJson(array('status' => 1, 'error' => 'No item provided.'), true);
		$item = get_post($item_id);
		if(!is_object($item) || $item->post_type != 'cart_items')
			self::SimpleJson(array('status' => 1, 'message' => 'Unknown error.'), true);
		//FIXME:!!! Need to search this item->ID in current user session
		$items = $this->GetCartItems();
		$style = @$_REQUEST['style'];
		if(!in_array($style, array('straight', 'infinity')))
			self::SimpleJson(array('status' => 1, 'message' => 'Unknown scarf style.'), true);
		$finded = false;
		foreach($items as $item) {
			if($item->ID == $item_id) {
				$item->scarf_style = $style;
				update_post_meta($item->ID, 'scarf_style', $style);
				break 1;
			}
		}
		$cnt = $this->CalcTotalItemsPrice($items);
		self::SimpleJson(array('status' => 0, 'price' => $cnt), true);
	}
	public function CountryDropdown($args = array()) {
		$defaults = array(
			'selected' => 'USA',
			'name' => 'user_country',
			'id' => 'user_country',
			'class' => 'country-dropdown',
			'echo' => true,
			'order_by' => 'post_title',
			'order' => 'ASC',
			'required' => true	
		);
		$args = wp_parse_args($args, $defaults);
		$countries = get_posts(
			array(
				'post_type' => 'countries',
				'post_status' => 'publish',
				'orderby' => $args['order_by'],
				'order' => $args['order'],
				'posts_per_page' => -1
		));
		$select = $this->FetchTemplatePart('country-dropdown.php', array('args' => $args, 'countries' => $countries));
		if($args['echo'])
			echo $select;
		else
			return $select;
	}
	public function StatesDropdown($args = array()) {
		$defaults = array(
			'selected' => '',
			'name' => 'user_state',
			'id' => 'user_state',
			'class' => 'states-dropdown',
			'echo' => true,
			'order_by' => 'post_title',
			'order' => 'ASC',
			'required' => true	
		);
		$args = wp_parse_args($args, $defaults);
		$states = get_posts(
			array(
				'post_type' => 'states',
				'post_status' => 'publish',
				'orderby' => $args['order_by'],
				'order' => $args['order'],
				'posts_per_page' => -1
		));
		$select = $this->FetchTemplatePart('states-dropdown.php', array('args' => $args, 'states' => $states));
		if($args['echo'])
			echo $select;
		else
			return $select;
	}
	public static function DiscountEnabled() {
		return (defined('USE_DISCOUNT_COUPONS') && USE_DISCOUNT_COUPONS == true);
	}
	public function CalcTotalItemsPrice($items) {
		if(empty($items))
			return 0;
		$price = 0;
		foreach($items as $item) {
			$style = 0;
			if($item->scarf_style == 'infinity')
				$style = 5;
			$price = ($price + ($this->GetDefaultPrice() + $style)) * $item->items_count;
			//Discount calc per scarf may been here...	
		}
		//discount for all items in cart goes here...
		if(self::DiscountEnabled()) {
			$coupon = self::GetSessionCoupon();
			if(!empty($coupon)) {
				$discount_percent = $coupon->discount_percent;
				$discount_amount = $price * ($discount_percent / 100);
				$price = $price - $discount_amount;	
			}	
		}
		return $price;
	}
	public function ScarfAccountPage($args = array()) {
		$wp_errors = new Wp_Error();
		$orders = array();
		$login_url = wp_login_url('/account');
		if(!is_user_logged_in()) {
			$wp_errors->add(0, "Please <a href='{$login_url}'>log in</a> to see the history of your purchases.");
		}
		$user = wp_get_current_user();
		$orders = $this->GetUserOrders($user->ID, 'all');
		return $this->FetchTemplatePart('account.php', array('wp_errors' => $wp_errors, 'user' => $user, 'orders' => $orders));
	}
	public function ScarfCartPage($args = array()) {
		$makescarf_url = get_permalink(MAKE_SCARF_PAGE_ID);
		if(!empty($_REQUEST['add']) && is_numeric($_REQUEST['add'])) {
			//I think we need to move this code somewhere
			$scarf = get_post($_REQUEST['add']);
			$this->LoadScarfData($scarf);
			//if(false == in_array($scarf->ID, $_SESSION['SCARF_SIMPLE_CART']['items'])) {
				//need to create a dublicate and add to cart_items
				$data = get_object_vars($scarf);
				unset($data['ID']);
				$_ = $this->GetRequiredMetas();
				$metas = array();
				foreach($_ as $key) {
					$metas[$key] = $data[$key];
					unset($data[$key]);
				}
				unset($data['order_id']);
				$data['post_status'] = 'draft';
				$wp_errors = new Wp_Error();
				$new_scarf_id = wp_insert_post($data, $wp_errors);
				$_SESSION['SCARF_SIMPLE_CART']['items'][] = $new_scarf_id;
				
				foreach($metas as $meta => $value) {
					update_post_meta($new_scarf_id, $meta, $value);
				}
				self::JsRedirect(get_permalink(CART_PAGE_ID));
				//return; 
			//}
			//wp_update_post(array('ID' => $scarf->ID, 'post_status' => 'draft'));
			/*if($scarf->post_type == 'cart_items')
				$_SESSION['SCARF_SIMPLE_CART']['items'][] = $scarf->ID;*/
		}
		if(empty($_SESSION['SCARF_SIMPLE_CART'])) 
			return "<div class='message notice'><p>Your cart is empty, <a href='{$makescarf_url}'>create a first scarf</a>.</p></div>";
		
		$items = $this->GetCartItems(true);
				
		return $this->FetchTemplatePart('simplecart.php', array('items' => $items, 'total_amount' => $this->CalcTotalItemsPrice($items)));
	}
	public function LoadScarfData($scarf) {
		if(!is_object($scarf)) return false;
		$metas = $this->GetRequiredMetas();
		foreach($metas as $meta) {
			$scarf->$meta = get_post_meta($scarf->ID, $meta, true);
		}
		$scarf->price = $this->GetDefaultPrice();
		return $scarf;
	}
	public function LoadOrderData($order) {
		if(!is_object($order))
			return false;
		$metas = $this->GetOrderMetas();
		foreach($metas as $meta) {
			$order->$meta = get_post_meta($order->ID, $meta, true);
			
		}
		if(empty($order->total_amount_paid))
			$order->total_amount_paid = '00.00';
		$order->shipping_address = $this->MakeShippingAddressString($order);
		$scarvs = maybe_unserialize(get_post_meta($order->ID, 'scarvs', true));
		$order->scarvs = array();
		if(empty($scarvs))
			return false;
		foreach($scarvs as $scarf) {
			$scarf = get_post($scarf);
			if(!is_object($scarf))
				continue;
			$order->scarvs[] = $this->LoadScarfData($scarf);
		}
		return $order;
	}
	public function MakeShippingAddressString($order) {
		if(!is_object($order))
			return false;
		$string = $order->user_country . ', ';
		if($order->user_country == 'USA')
			$string .= $order->user_state.', ';
		elseif(!empty($order->user_province))
			$string .= $order->user_province.', ';
		$string .= $order->user_city.', '.$order->user_address;
		return $string;
	}
	public function GetUserOrders($user_id, $mode) {
		$user_id = intval($user_id);	
		global $wpdb;
		$post_status = ' AND `post_status` = \'draft\'';
		if($mode == 'all')
			$post_status = " AND (post_status = 'draft' OR post_status = 'publish')";
		$orders = $wpdb->get_results("SELECT * FROM `{$wpdb->posts}` WHERE (`post_author` = {$user_id} AND `post_type` = 'orders'{$post_status})");
		if(!empty($orders)) {
			foreach($orders as $order) {
				$this->LoadOrderData($order);
			}
		}
		return $orders;
	}
	public function GetDefaultPrice() {
		return $this->options->GetOption('default_price', 68);
	}
	public function GetDefaultDeliveryPrice() {
		return $this->options->GetOption('default_delivery_price', 35);
	}
	public function GetCartItems($for_cart = false) {
		if(empty($_SESSION['SCARF_SIMPLE_CART']['items']))
			return false;
		$items = $_SESSION['SCARF_SIMPLE_CART']['items'];
		$stack = array();
		foreach($items as $key => $value) {
			$post = get_post($value);
			if(in_array($post->ID, $stack)) {
				unset($items[$key]);
				continue;
			}
			
			if(!is_object($post) || in_array($post->ID, $stack)) {
				unset($items[$key]);
				continue;
			}
			$stack[] = $post->ID;
			if($post->post_status == 'trash' || ($post->post_status == 'publish' && true == $for_cart)) {
				unset($items[$key]);
				continue;
			}
			$items[$key] = $this->LoadScarfData($post);
			/*$meta = get_post_meta($post->ID, 'order_id');
			if(!empty($meta))
				unset($items[$key]);*/
		}
		return $items;
	}
	public function WpInit() {
		@session_start();
		if($_SERVER['REQUEST_URI'] == '/paypal-callback') {
			file_put_contents($_SERVER['DOCUMENT_ROOT'].'/log.txt', self::Dump($_REQUEST));
			die("Yes");
		}
		if(empty($_SESSION['SCARF_SIMPLE_CART']['items']))
			$_SESSION['SCARF_SIMPLE_CART']['items'] = array();
		if(!empty($REQUEST_URI['oid']) && $_REQUEST['oid']) {
			$__ = $this->GetOrderScarvs($_REQUEST['oid']);
			if(!empty($__) && is_array($__)) {
				foreach($__ as $_) {
					wp_update_post(array('ID' => $_->ID, 'post_status' => 'draft'));
					if(false == in_array($_->ID, $_SESSION['SCARF_SIMPLE_CART']['items'])) {
						$_SESSION['SCARF_SIMPLE_CART']['items'][] = $_->ID;
					}
				}
			}
		}
		if(self::IsPostRequest() && !empty($_REQUEST['scarf_action'])) {
			switch($_REQUEST['scarf_action']) {
			case 'save_artwork':
			default:
				$data = $_POST;
				if(empty($data['scarf']['text'])) break;
				if(!empty($data['save_colors'])) {
					$_SESSION['save_colors'] = array('text' => $data['scarf']['text_color'], 'background' => $data['scarf']['background_color']);
				}
					$scarf = $_POST['scarf'];
					$allowed = '<p><br><i><strong><b><strike><span><div>';	
					foreach($scarf as $key => $value) {
						$scarf[$key] = strip_tags($value, $allowed);
					}
					foreach($data as $key => $value) {
						if(!is_array($value)) {
							$data[$key] = strip_tags($value, $allowed);
						}
					}
					$post_data = array(
						'post_title' => '',
						'post_name' => '',
						'post_content' => stripslashes($data['scarf']['text']),
						'post_type' => 'cart_items',
						'post_status' => 'draft'
					);
					$wp_errors = new WP_Error();
					$post_id = wp_insert_post($post_data, $wp_errors);
					if(!empty($wp_errors->errors)) {
						return "<div class='message error'>There are an error occured while save your order. Please, try again later.</div>";
					}
					$title = "Scarf item #{$post_id}";
					wp_update_post(array('ID' => $post_id, 'post_title' => $title));
					$metas = $this->GetRequiredMetas();
					foreach($metas as $meta) {
						update_post_meta($post_id, $meta, $scarf[$meta]);
					}
					update_post_meta($post_id, 'items_count', 1);
					$items = array($post_id);
					if(!empty($_SESSION['SCARF_SIMPLE_CART']['items']))
						$items = array_merge($items, $_SESSION['SCARF_SIMPLE_CART']['items']);
					$_SESSION['SCARF_SIMPLE_CART']['items'] = $items;
					$cart_page = get_permalink(CART_PAGE_ID);
					Header("Location: ".$cart_page);
					exit();
					break;
			case 'process_order':
					
				break;
			}
		}
		$sid = @intval($_REQUEST['sid']);

		if(!empty($sid)) {
			$scarf = get_post($sid);
			if(!is_object($scarf) || $scarf->post_type != 'scarf') {
				//TODO: Check that this user can view this scarf!
				$this->LoadScarfData($scarf);
				return $this->FetchTemplatePart('scarf_preview.php', array('scarf' => $scarf));
			}
		}
	}
	public function GetPostByTitle($title, $post_type) {
		global $wpdb;
		$title = $wpdb->escape($title);
		$post_type = $wpdb->escape($post_type);
		$post = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}posts WHERE post_title = '{$title}' AND post_type = '{$post_type}'");
		return $post;
	}
	public function GetOrderMetas() {
		return array(
			'order_status',
			'first_name',
			'last_name',
			'user_email',
			'user_phone',
			'user_country',
			'user_state',
			'user_city',
			'user_zip_code',
			'user_address',
			'order_tax',
			'user_province',
			'total_amount_paid',
			'shipping_address',
			'delivery_method_value',
			'coupon_id'
			);
	}
	public function GetRequiredMetas() {
		return array(
			'text_color',
			'background_color',
			'font_variant',
			'text',
			'send_artwork',
			'instructions',
			'items_count',
			'scarf_style'
		);
	}
	public function MakeScarfPage($args = array()) {
		$text = '';
		/*if(!empty($_REQUEST['lid'])) {
			$post = get_post($_REQUEST['lid']);
			//some validation of the post!!! Its important!
			$text = "";
			if($post->post_type == 'post' && $post->post_status == 'publish' && has_term(LIBRARY_TAX_ID, 'category', $post)) {
				$text = $post->post_content;
			}
			$data['text'] = $text;
		}*/
		$data = $_REQUEST;
		//check for small errors
		$wp_errors = new Wp_Error();
		if(empty($_REQUEST['scarf']['text']) && !empty($_REQUEST['add_to_cart']))
			$wp_errors->add(0, "<div style='color: red;'>Your scarf text can not be empty.</div>");
		
		$data['text'] = @stripslashes(strip_tags($_REQUEST['text'], '<b><strong><pre><i><p><u><s><br>'));
		$text = $data['text'];	
		if(!empty($_SESSION['save_colors'])) {
			$data['background_color'] = str_replace('#', '', $_SESSION['save_colors']['background']);
			$data['text_color'] = str_replace('#', '', $_SESSION['save_colors']['text']);
		}
		if(!empty($_REQUEST['text_color']))
			$data['text_color'] = $_REQUEST['text_color'];
		if(!empty($_REQUEST['background_color']))
			$data['background_color'] = $_REQUEST['background_color'];
		return $this->FetchTemplatePart('makescarf.php', array('text' => $text, 'data' => $data, 'wp_errors' => $wp_errors));
	}
	public function GetLatestUserOrderData($user_id) {
		global $wpdb;
		$user_id = intval($user_id);
		$order = $wpdb->get_row("SELECT * FROM `{$wpdb->posts}` WHERE (`post_author` = {$user_id} AND `post_type` = 'orders' AND `post_status` IN ('publish', 'draft')) ORDER BY ID DESC LIMIT 1");	
		$this->LoadOrderData($order);
		if(is_object($order))
			return get_object_vars($order);
		return false;
	}
	public function GetOrderItems($order_id) {
		$order_id = intval($order_id);
		global $wpdb;
		$rows = $wpdb->get_results("SELECT * FROM `{$wpdb->postmeta}` WHERE (`post_id` = '{$order_id}' AND `meta_key` = 'order_id')");
		return $rows;
	}
	public function MakeOrderPage($args = array()) {
		
		$current_user = wp_get_current_user();
		$data = array();
		if(!empty($current_user) && is_user_logged_in()) {
			$data = $this->GetLatestUserOrderData($current_user->ID);
		}
		if(!empty($_REQUEST['oid']))
			$items = $this->GetOrderScarvs($_REQUEST['oid']);
		else
			$items = $this->GetCartItems(false);
		$delivery_conf = $this->LoadDeliveryInfo();
		if(@$_REQUEST['scarf_action'] == 'process_order') {
		//FIXME: Check all fields
				$wp_errors = new Wp_Error();

				$items_price = $this->CalcTotalItemsPrice($items);
				$country = $_POST['user_country'];
				$data = $_POST;
				if(!empty($data['draft_order_id'])) {
					$draft_order = get_post($data['draft_order_id']);
					
				}
				foreach($data as $key => $value) {
					$data[$key] = strip_tags($value);
				}
				if(empty($data['user_email'])) {
					$wp_errors->add(0, "You must enter an email.");
				} elseif(!is_email($data['user_email'])) {
					$wp_errors->add(0, "You must enter a valid email address.");
				}
				$delivery_conf = $this->LoadDeliveryInfo();
				if(!empty($wp_errors->errors)) {
					return $this->FetchTemplatePart('scarforder.php', array('errors' => $wp_errors, 'data' => $data, 'delivery_info' => $delivery_conf));
				}
				$user = get_user_by('email', $data['user_email']);
				if(!empty($user) && (empty($current_user) || @$current_user->data->user_email != @$data['user_email'])) {
				$login_url = wp_login_url('/order');
				$wp_errors->add(ERROR_CODE_EMAIL_IN_USE, "Email has already been taken. Please, <a href='{$login_url}'>log in</a> to existing account. The password has been sent to your email address when you placed the first order.");
				} elseif(empty($user) && !empty($data['user_email'])) {
					$user_id = $this->CreateUserIfNotExists($data['user_email']);
				} elseif(!empty($user)) {
					$user_id = $user->ID;
				}
				
				foreach($items as $k => $item){
					if($item->post_author != @$user_id && $item->post_author != 1)
						unset($items[$k]);
				}

				
				//if(empty($items))
				//	$wp_errors->add(0, 'Sorry. your cart is empty, you can not continue to shipping info. <a href="'.get_permalink(MAKE_SCARF_PAGE_ID).'">Make a scarf</a>.');
				if(!empty($wp_errors->errors)) {
					return $this->FetchTemplatePart('scarforder.php', array('data' => $data, 'wp_errors' => $wp_errors, 'delivery_conf' => $delivery_conf) );
				}		
				
				$tax = false;
				if('USA' != $country) {
					unset($_POST['state']);

				} else {
				
					$state = $this->GetPostByTitle($data['user_state'], 'states');
					//Get tax
					
					if(!empty($state)) {
						$tax = get_post_meta($state->ID, 'tax', true);
						$tax = (float) $tax;
					}
				}
				$post_data = array(
					'post_title' => '',
					'post_status' => 'draft',
					'post_type' => 'orders',
					'post_author' => $user_id
				);
				$metas = $this->GetOrderMetas();
				if(empty($draft_order)) {
					$post_id = wp_insert_post($post_data);
					$post_title = 'Scarvs Order #'.$post_id;
					wp_update_post(array('ID' => $post_id, 'post_title' => $post_title));
				} else {
					$post_id = $draft_order->ID;
				}
				foreach($metas as $meta) {
					if(empty($data[$meta]))
						$data[$meta] = '';
					update_post_meta($post_id, $meta, $data[$meta]);
				}
				
				$order = get_post($post_id);
				$items = $this->GetCartItems(false);
				$total_price = $items_price;
				$products_price = $total_price;
				$tax_value = 0;
				if($tax) {
					$tax_value = round($total_price * ($tax / 100), 2);
					
					$total_price = $total_price + $tax_value;
				}
				/*$country = $this->GetPostByTitle($data['user_country'], 'countries');
				if(!empty($country)) { 
					$delivery = get_post_meta($country->ID, 'delivery_cost', true);
					if('' == $delivery) {
						$delivery = $this->GetDefaultDeliveryPrice();
					}
				}*/
				//get a delivery coast
				$delivery = 0;
				$delivery = @$delivery_conf[$data['user_country']][$data['delivery_method_value']]['coast'];
				$delivery_title = @$delivery_conf[$data['user_country']][$data['delivery_method_value']]['title'];
				if(empty($delivery)) {
					$delivery = $delivery_conf['Default'][$data['delivery_method_value']]['coast'];
					$delivery_title = $delivery_conf['Default'][$data['delivery_method_value']]['title'];
				}
				update_post_meta($order->ID, 'delivery_title', $delivery_title);
				if(!empty($delivery)) {
					$total_price += $delivery; 
				}
				$scarvs = array();
				foreach($items as $item) {
					/*if($item->post_author != $user_id)
						continue;*/
					wp_update_post(array('ID' => $item->ID, 'post_author' => $user_id, 'post_status' => 'publish'));
					$scarvs[] = $item->ID;
					update_post_meta($item->ID, 'order_id', $post_id);
				}
				update_post_meta($post_id, 'scarvs', maybe_serialize($scarvs));
				//Set a coupon as used!!!
				if(!empty($data['coupon_id'])) {
					$coupon = self::GetCouponById($data['coupon_id']);
					if(!empty($coupon)) {
						wp_trash_post($coupon->ID);
					}
				}
				return $this->FetchTemplatePart('pay.php', array(
							'total_price' => $total_price,
							'delivery' => $delivery,
							'tax' => $tax,
							'state' => @$state,
							'country' => @$country,
							'order' => $order,
							'products_price' => $products_price,
							'tax_value' => $tax_value,
							'data' => $data,
							'wp_errors' => $wp_errors,
							'scarvs' => $scarvs,
							'delivery_conf' => $delivery_conf,
							'delivery_title' => $delivery_title
						));
		}
		return $this->FetchTemplatePart('scarforder.php', array('data' => $data, 'scarvs' => $items, 'delivery_conf' => $delivery_conf));
	}
	public function CountCartItems() {
		$items = $this->GetCartItems(true);
		if(empty($items))
			return false;
		return count($items);
	}
	public function LoadDeliveryInfo() {
		$_info =  parse_ini_file(dirname(__FILE__).'/delivery.ini', true);
		$_ = array();
		foreach($_info as $country => $info) {
			$__ = array();
			foreach($info as $method => $coast) {
				$__[md5($method)] = array('title' => $method, 'coast' => $coast);
			}
			$_[$country] = $__;
		}
		return $_;
	}
	public function RegisterScripts() {
		wp_enqueue_script('jquery');
		wp_enqueue_script('jquery-ui-tooltip');
		wp_enqueue_style('jquery-ui-tooltip', get_template_directory_uri().'/css/tooltips.css');
		wp_enqueue_script('sceditor-default', get_template_directory_uri().'/sceditor/jquery.sceditor.min.js');
		wp_enqueue_script('sceditor-xhtml', get_template_directory_uri().'/sceditor/jquery.sceditor.xhtml.min.js');
		wp_enqueue_style('sceditor-style', get_template_directory_uri().'/sceditor/themes/default.min.css');
		wp_enqueue_script('jquery-ui-draggable', null, null, null, false);
		wp_enqueue_script('jquery-touch-punch', get_template_directory_uri().'/js/jquery.ui.touch-punch.min.js', array('jquery-ui-draggable'), '1.0.1', false);
		wp_enqueue_script('mycolorpicker', get_template_directory_uri().'/js/colorpicker.js', array('jquery'), time());
		wp_enqueue_script('eye', get_template_directory_uri().'/js/eye.js');
		wp_enqueue_script('jquery-reveal', get_template_directory_uri().'/js/jquery.reveal.js');
		wp_enqueue_script('jquery-placeholder', get_template_directory_uri().'/js/jquery.placeholder.min.js');
		wp_enqueue_script('flexslider', get_template_directory_uri().'/js/jquery.flexslider.js');
		wp_enqueue_script('scarf-main-js', get_template_directory_uri().'/js/main.js');
	}
	public static function Me($args = array(), $options = array()) {
		static $instance;
		if(!is_object($instance))
			$instance = new self($args, $options);
		return $instance;
	}	
}
$theme = MakeScarf_Theme::Me(/*array('DEBUG' => true)*/);
