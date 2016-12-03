<?php
/**
 * This module works only if Woocommerce is available on this web-site.
 * @version 0.0.1
 * @author Shell
 * TODO: Need to move all Woocommerce specified code here. 
 * */
namespace Pure\Module\Woo;
class Module extends \Pure\Module {
	protected $_cart;

	protected function _init() {
		add_action('woocommerce_init', array($this, 'hook_woocommerce_init'));
	}

	public function hook_woocommerce_init() {
		
	}
	/**
	 * This method gets a Woocommerce product by it's ID. The code moved from \PureLib\Utils.
	 * @param int $product_id The product ID.
	 * @return WC_Product_Simple
	 * */	
	public function get_product_by_id($product_id) {
		static $factory;
		if(!is_object($factory))
			$factory = new \WC_Product_Factory();
		if(empty($product_id) || !is_numeric($product_id) || intval($product_id) < 0) {
			throw new \Pure\Exception("Can not get a product. Product ID must be specified and valid numeric value > 0.");
		}
		$product_id = intval($product_id);
		$product = $factory->get_product($product_id);
		return $product;
	}
	/**
	 * This method gets a valid instance of a cart object, and sets a current request as a CART request.
	 * Because a lot of internal Woocommerce methods works differently in those pages.
	 * @return WC_Cart
	 * */
	public function get_cart() {
		if(empty($this->_cart)) {
			if(!defined('WOOCOMMERCE_CART')) define('WOOCOMMERCE_CART', true);	
			$this->_cart = WC()->cart;
			$this->_cart->calculate_totals();
		}
		return $this->_cart;
	}
	/**
	 * This method adds a product to a woocommerce cart for current user.
	 * @param int $product_ID The product ID which will be added to cart.
	 * @return boolean
	 * */
	public function add_to_cart($product_ID, $quantity = 1, $variation_id = 0, $variation = array(), $cart_item_data = array()) {
		return WC()->cart->add_to_cart($product_ID, $quantity, $variation_id, $variation, $cart_item_data);
	}
	/**
	 * This method sets a product attributes for Woocommerce existing product.
	 * @param int $product_ID The ID of existing Woocommerce product.
	 * @param array $attributes An array of attributes which will be set for this product.
	 * **/
	public function set_product_attributes($product_ID, $attributes) {
		if(empty($attributes) || !is_array($attributes)) return false;
		
		$_attributes = array();
		foreach($attributes as $attribute => $value) {
			if(false === strpos($attribute, 'pa_')) $attribute = 'pa_' . $attribute;
			$_attributes[$attribute] = $value;
		}
		$attributes = $_attributes;
		
		try {
			return \PureLib\Utils::woo_set_product_attributes($product_ID, $attributes);
		} catch(\Exception $e) {
			//TODO: Seems we can do something other with those exception?
			throw $e;
		}
	}
	/**
	 * TODO: */
	public function get_registered_attributes() {}
	/**
	 * This method returns a "pretty" attribute title form a realy pa_ attribute slug.
	 * @param string $attribute_real_slug The real slug with pa_ prefix or without.
	 * @return string a pretty attribute title, if this is a valid and existing attribute.
	 * */
	public function get_product_attr_name($attribute_real_slug)  {
	
	}
	/**
	 * This method helps to easily save the new or existing Woocommerce product.
	 * It can pull the basic $post_data attributes for Woocommerce product.
	 * @param array $product_data
	 * @return int Returns a new or update post_ID
	 * @throws \Pure\Exception Throws an exception if we get an WP_Error somewhere.
	 * */
	public function save_product($product_data, $log = null) {
		
		if(empty($product_data)) return false;
		if(is_object($product_data)) {
			if(($product_data instanceof \WP_Post))
				$product_data = get_object_vars($product_data);
		}

		if(!is_array($product_data)) {
			return false;
		}
		if(empty($product_data['post_type']))
			$product_data['post_type'] = 'product';
		if(empty($product_data['post_status']))
			$product_data['post_status'] = 'draft';
		if(empty($product_data['product_type']))
			$product_data['product_type'] = 'simple';
		if(empty($product_data['regular_price']))
			$product_data['regular_price'] = 1;
	
		$wp_error = null;
		$product_ID = wp_insert_post($product_data, true);
		$post_id = $product_ID;

		if(is_wp_error($product_ID)) {
			throw new \Pure\Exception("Can not insert new post and save a product. \wp_insert_post gots an WP_Error. Message: " . $product_ID->get_error_message() );	
		}
		wp_set_object_terms($post_id, $product_data['product_type'], 'product_type');
		update_post_meta( $post_id, '_visibility', 'visible' );
		update_post_meta( $post_id, '_stock_status', 'instock');
		update_post_meta( $post_id, 'total_sales', '0');
		update_post_meta( $post_id, '_downloadable', 'no');
		update_post_meta( $post_id, '_virtual', 'no');
		update_post_meta( $post_id, '_regular_price', $product_data['regular_price'] );
		@update_post_meta( $post_id, '_sale_price', $product_data['sale_price'] );
		update_post_meta( $post_id, '_purchase_note', "" );
		update_post_meta( $post_id, '_featured', "no" );
		update_post_meta( $post_id, '_weight', "" );
		update_post_meta( $post_id, '_length', "" );
		update_post_meta( $post_id, '_width', "" );
		update_post_meta( $post_id, '_height', "" );
		update_post_meta( $post_id, '_sku', "");
		update_post_meta( $post_id, '_product_attributes', array());
		update_post_meta( $post_id, '_sale_price_dates_from', "" );
		update_post_meta( $post_id, '_sale_price_dates_to', "" );
		update_post_meta( $post_id, '_price', $product_data['regular_price'] );
		update_post_meta( $post_id, '_sold_individually', "" );
		update_post_meta( $post_id, '_manage_stock', "no" );
		update_post_meta( $post_id, '_backorders', "no" );
		update_post_meta( $post_id, '_stock', "" );
		if(!empty($product_data['attributes'])) {
			$this->set_product_attributes($product_ID, $product_data['attributes']);
		}	
		return $product_ID;
	}
}
