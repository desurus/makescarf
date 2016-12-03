<?php

namespace Pure\Helper;

/*
 * IMPORTANT FIXME: We need to cache this results, because it's a little highload! 
 * **/
class Wordpress {
	/**
	 * This method tries to get a current post in llop or provided by a veriable, mainly used in internal methods.
	 * @param $post mixed Default is null, provided post.
	 * @return mixed.
	 * */
	public static function get_current_post($_post = null) {
		if(is_object($_post)) return $_post;
		if(is_numeric($_post)) return get_post($_post);
		
		global $post;
		$_post = $post;
		return $_post;
	}
	public static function get_current_active_theme_name() {
		$theme = wp_get_theme();
		return $theme->get('Name');
	}

	public static function get_parent_terms($term) {
		$parents = array();
		if(!is_object($term))
			$term = get_term($term);
		if($term->parent == 0) 	return $parents;
		while(true) {
			$term = get_term($term->parent);
			$parents[] = $term;
			if($term->parent == 0) break;
		}
		return $parents;
	}
	public static function is_post_in_term($term_id, $post_id = null, $taxonomy = 'category') {
		if(empty($post_id)) {
			$post = self::get_current_post();
			$post_id = $post->ID;
		}
		if(empty($term_id)) return false;
		if(empty($post_id)) return false;
		if(!is_string($taxonomy)) return false;

		$terms = wp_get_object_terms($post_id, $taxonomy);
		if(empty($terms)) return false;
		foreach($terms as $term) {
			if($term_id == $term->term_id || self::is_term_in_term($term->term_id, $term_id)) return true;
		}
		return false;
	}
	/**
	 * This method checks if some post  (page) is has a some parent with specified ID.
	 * @param mixed $parent_post_ID
	 * @param mixed $current_post_ID 
	 * */
	public static function is_post_in_parent($parent_post_ID, $current_post_ID = null) {
		if(null == $current_post_ID) {
			$current_post = self::get_current_post();
		} else {
			//
		}
		if(!is_object($parent_post_ID)) {
			$parent_post = get_post($parent_post_ID);
		} else {
			$parent_post = $parent_post_ID;
		}
		
	}
	/**
	 * Get a current post parents 
	 * */
	public static function get_post_parents($post = null) {
		if(null == $post) {
			$post = self::get_current_post();
		} else {
			if(is_numeric($post)) $post = get_post($post);
		}
		if(!is_object($post)) {
			return array();
		}
		$parent = $post->post_parent;
		$parents = array();
		while($parent != 0) {
			$parents[] = $parent;
			$_ = get_post($parent);
			if(!$_) $parent = 0;
			$parent = $_->post_parent;
		}
		return $parents;
	}
	/**
	 * This method searches if the current Wordpress request can be assigned to a some term as a child term request or a current post in this term...
	 * */
	public static function is_request_in_term($term_id, $taxonomy = 'product_cat') {
		$object = get_queried_object();
		if($object instanceof \WP_Post) {
			if($object->post_type == 'page') return false;
			$terms = wp_get_object_terms($object->ID, $taxonomy);
			if(empty($terms)) return false;
			foreach($terms as $term) {
				if($term_id == $term->term_id || self::is_term_in_term($term->term_id, $term_id)) return true;
			}	
			return false;
		}	
		if($object instanceof \WP_Term) {
			if($object->term_id == $term_id) return true;
			return self::is_term_in_term($object->term_id, $term_id);	
		}	
		return false;
	}
	public static function is_term_in_term($search_term, $parent_term_id) {
		$search_term_id = $search_term;
		if(is_object($search_term)) {
			$search_term_id = $search_term->term_id;
		}
		$parent_terms = self::get_parent_terms($search_term_id);
		if(empty($search_term_id) || !is_numeric($search_term_id)) {
			return false;	
		}
		if(empty($parent_terms)) return false;
		foreach($parent_terms as $parent) {
			if($parent->term_id == $parent_term_id) return true;
		}
		return false;
	}
	/*
	 * This method return an array of registered Wordpress menu locations.
	 * @return array
	 * **/
	public static function get_menu_locations() {
		$locations = get_registered_nav_menus();
		return $locations;		
	}
	/**
	 * This method checks if current page requested in a customizer...
	 * */
	public static function in_customizer() {
		global $wp_customize;
		if(empty($wp_customize)) return false;
		return true;
	}
}
