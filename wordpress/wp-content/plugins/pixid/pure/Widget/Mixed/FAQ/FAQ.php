<?php

/*
 * This is a simple widget which provide a simple list of Frequently Asked Questions. 
 * This widget requires an easy to install and use plugin "arconix-faq". Which can be found here: https://wordpress.org/plugins/arconix-faq/
 * TODO: We need to have a some automatic tool to install and view widget dependencies from extended plugins|themes or libraries, same as and from internal?
 * */

namespace Pure\Widget\Mixed\FAQ;

class FAQ extends \Pure\Widget {
	
	public function get_posts() {
		$args = array(
				'post_type' => 'faq',
				'post_status' => 'publish',
				'posts_per_page' => -1,
				'order' => 'ASC',
				'orderby' => 'post_order'
		);	
		$posts_query = new \WP_Query(
			$args	
		);

		$posts = $posts_query->posts;
		return $posts;
	}
	public function widget() {
		$posts = $this->get_posts();	
		$this->display_template(array('posts' => $posts));	
	}

	public function get_simple_edit_buttons() {
		return array(
			array(
				'icon' => 'edit',
				'title' => 'Edit Questions and answers',
				'link' => admin_url('edit.php?post_type=faq')
			)
		);
	}	
} 
