<?php

/**
 * Available from 0.0.21
 * This class contains a code which would be helpful for websites usigns Woocommerce as a ecommerce backend on a Wordpress websites.
 * The link to documentation will be provided later.
 * @author Shell
 * @version 0.0.21
 * */
namespace PureLib\Utils;

if(!function_exists('wc_clean')) {
	throw new \Exception("Seems you try to use some methods from Woo utils. But we can not find a Woocommerce plugin in your environment. Please, install end enable it first.");
}

class Woo {
	/**
	 * FIXME: This method needs a testing for stability.
	 * This method sets an attributes array to some provided product id.
	 * @param int $product_id Product unique identifier which attributes will be changed.
	 * @param array $attrs An valid non empty array with array('attribute' => value[s])
	 * @param boolean $update From version 0.0.21 now we can do a replacing of existing attributes, or append a new. This last param work need to be checked.
	 * */
	public static function set_product_attributes($product_id, $attrs, $update = true) {
		if(!is_numeric($product_id)) throw new \Exception("Product ID must be a valid numeric value.");
		
		if(!is_array($attrs) || empty($attrs)) throw new \Exception("Attributes must be a valid and not empty array.");
		
		$attributes = array();
		foreach($attrs as $attribute_name => $attribute_value) {	
			wp_set_object_terms($product_id, $attribute_value, $attribute_name, false);
			$attributes[ sanitize_title( $attribute_name ) ] = array(
			        'name'          => wc_clean( $attribute_name ),
			        'value'         => $attribute_value, 
			        'is_visible'    => true, // this is the one you wanted, set to true
			        'is_variation'  => false, // set to true if it will be used for variations
		        	'is_taxonomy'   => true // set to true
			);	
		}
		$attributes_meta = array();
		$old_attributes_meta = get_post_meta($product_id, '_product_attributes', true);
		$attributes_meta = $old_attributes_meta;
		foreach($attributes as $sanitized_name => $attribute_value) {
			$attributes_meta[$sanitized_name] = $attribute_value;
		}	
		
		update_post_meta( $product_id, '_product_attributes', $attributes_meta );
		return true;
	}
}
