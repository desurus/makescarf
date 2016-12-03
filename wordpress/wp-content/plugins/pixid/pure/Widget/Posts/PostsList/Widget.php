<?php

namespace Pure\Widget\Posts\PostsList;

class Widget extends \Pure\Widget {
	public function get_default_posts_args() {
		return array(
				'post_type' => 'post',
				'post_status' => 'publish',
				'posts_per_page' => 5
		);
	}
	public function build_posts_args() {
		$posts_args = $this->args()->get_array();
		$default_posts_args = $this->get_default_posts_args();
		$posts_args = wp_parse_args($posts_args, $default_posts_args);
		/*
		 * Now we support a custom fields meta linked elements */
		if($this->args()->get('acf_linked_metaname', false)) {
			$metaname = $this->args()->get('acf_linked_metaname');	
			$post_id = $this->args()->get('acf_linked_to_post');
			if(empty($post_id)) {
				global $post;
				$post_id = $post->ID;
			}
			if(!empty($metaname) && !empty($post_id)) {
				//FIXME: Next code needs a lot of fixes!!!!!!!!
				$linked_data = get_post_meta($post_id, $metaname, true);
				if(empty($linked_data)) {
					$linked_data = -1;
				}
				if(!is_array($linked_data)) {
					$linked_data = array($linked_data);
				}
				$posts_args['post__in'] = $linked_data;	
			}	
		}
		$args = $this->args();
		/*
		 * Filter support for custom filed... */
		if($args->get('meta_key')) {
			$posts_args['meta_key'] = $args->get('meta_key');
		}
		if($args->get('meta_value')) {
			$posts_args['meta_value'] = $args->get('meta_value'); 
		}
		return $posts_args;
	}
	public function get_posts() {
		$default_posts_args = $this->get_default_posts_args();
		if(!$this->args()->get('from_query')) {
			$posts_args = $this->build_posts_args();		

			$posts_query = new \WP_Query(
				$posts_args	
			);

			$posts = $posts_query->posts;
		} else {
			global $wp_query;
			$posts = $wp_query->posts;	
		}
		return $posts;
	}
	public function get_simple_edit_buttons() {
		$post_type = $this->args()->get('post_type', 'post');
		$edit_link = admin_url("edit.php?post_type={$post_type}");
		$buttons = array(
			array(
				'icon' => 'edit',
				'title' => 'Edit posts list',
				'link' => $edit_link
			)
		);
		return $buttons;
	}
	public function widget() {	

		$posts = $this->get_posts();
		$this->display_template(array( 'posts' => $posts ));		
	}	
}
