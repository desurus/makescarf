<?php

namespace MakeScarf\Widget\TextLibrary;

class Widget extends \Pure\Widget {
	public function widget() {
		//Get all sub-categories
		$library_category_id = $this->args()->get('library_category_id', 3);
		$taxonomies = array(
			'category'
		);
		$args = array(
			'parent' => $library_category_id
		);
		$terms = get_terms($taxonomies, $args);
		$data = array();
		foreach($terms as $term) {
			$posts = get_posts(
				array(
					'post_type' => 'post',
					'post_status' => 'publish',
					'posts_per_page' => -1,
					'cat' => $term->term_id
				)
			);
			$data[$term->term_id] = array(
				'title' => $term->name,
				'id' => $term->term_id,
				'items' => $posts
			);
		}
		if($this->is_post_request()) {
			$selected_posts = array();
			if(!empty($_POST['selected_posts']) && is_array($_POST['selected_posts'])) {
				foreach($_POST['selected_posts'] as $post_ID) {
					$post_ID = intval($post_ID);
					if(empty($post_ID)) continue;
					$selected_posts[] = $post_ID;
				}
			}	
			if(!empty($selected_posts)) {
				//TODO: We need to have a some projects configurations, because some data need to be stored as a config, such a page urls, or page IDs for service pages...
				$url = $this->args()->get('constructor_page_url', '/make-your-scarf');
				$url = add_query_arg(array('select_posts' => $selected_posts), $url);
				//Now we can redirect here!!!!!!!!!
				Header("Location: {$url}");
				exit();	
			}
		}	
		$this->display_template(array('data' => $data, 'encoded_data' => json_encode($data)));
	}
}
