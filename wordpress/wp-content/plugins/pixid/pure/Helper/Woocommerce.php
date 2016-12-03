<?php
/*
 * This is a helper class for store a frequently used functions in Woocommerce projects websites.
 * @author Shell
 * @version 0.1 Initial 
 * FIXME: We need to check, if the woocommerce plugin installed and enabled in current environment.
 * */
namespace Pure\Helper;
class Woocommerce {
	/**
	 * This method just checks if a woocommerce plugin available...
	 * @return boolean
	 * */
	public static function enabled() {
		return function_exists('WC');	
	}
	public static function get_product_attribute_term($attribute, $product = null) {
		if(null === $product) {
			global $post;
			$product = get_product($post->ID);
		}
		if(!is_object($product)) {
			//FIXME: Seems we can not get a valid product object, so can not continue execution
			return false;
		}
		$attribute_value = $product->get_attribute($attribute);
		if(empty($attribute_value)) {
			//FIXME: Some erro again, we can not continue
			return false;
		}
		$term = get_term_by('name', $attribute_value, "pa_{$attribute}");
		return $term;
	}


	public static function is_product_in_category($product, $category) {
		//Check if specified product in some categories
		if(!is_array($category)) {
			$category = array($category);
		}
		if(!is_object($product)) {
			//TODO: ???
			return false;
		}	
		
		if(!is_array($category) || empty($category)) return false;
		$product_cats = wp_get_post_terms($product->post->ID, 'product_cat');	
			
		foreach($product_cats as $cat) {	
			//FIXME: We need to call this method, because it's important!!!! if(Wordpress::is_term_in_term($cat->term_id, $category)) return true;
			if(false !== array_search($cat->term_id, $category)) return true;
			if(false !== array_search($cat->parent, $category)) return true;
		}	
		return false;
	}
	
}
