<?php
namespace Pure\Helper;
class Post {
	public function in_category($category, $post = null) {
		if(null == $post) {
			$post = Wordpress::get_current_post($post);
		}
		if(!is_object($post)) return false;
		$post_id = $post->ID;
		return self::in_term('category', $category, $post_id);	
	}
	public function in_term($taxonomy, $_term, $post = null) {
		if(!is_numeric($post)) {
			if(null == $post) {
				$post = Wordpress::get_current_post();
			}	
			if(!is_object($post)) return false;
			$post_id = $post->ID;
		} else {
			$post_id = $post;
		}
		$terms = wp_get_post_terms($post_id, $taxonomy);
		if(empty($terms)) return false;
		foreach($terms as $term) {
			
			if($term->term_id == $_term) return true;
		}
		return false;
	}
}
