<?php

namespace MakeScarf\Widget\Scarves;
class Widget extends \Pure\Widget\Posts\PostsList\Widget {
	/*public function is_extended_widget() {
		return true;
	}*/
	public function get_default_posts_args() {
		$user = PM()->User()
			->get_current_user();	
		$defaults = array_merge(parent::get_default_posts_args(), array(
			'post_type' => 'product',
			'post_status' => array('publish', 'private'),
			'author' => PM()->User()->get_current_user_ID(),
			'posts_per_page' => -1	
		));
		return $defaults;
	}
	public function widget() {
		if(!PM()->User()
			->is_logged_in()) {
			return $this->error_box("You are not logged in to view this page. <a href='/account/login/'>Login</a>.");		
		}
		$posts = get_posts($this->get_default_posts_args());
		$this->display_template(compact('posts'))	;
	}
}
