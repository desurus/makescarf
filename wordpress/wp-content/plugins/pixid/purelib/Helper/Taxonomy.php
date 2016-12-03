<?php

namespace PureLib\Helper;

class Taxonomy extends \PureLib\Helper {
	public static function is_registered_taxonomy($taxonomy) {
		global $wp_taxonomies;
		if(empty($taxonomy) || !is_string($taxonomy) || empty($wp_taxonomies) || !is_array($wp_taxonomies)) return false;
		return isset($wp_taxonomies[$taxonomy]);	
	}	
}
