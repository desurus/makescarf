<?php

/*
 * This is a part of temporary widgets, which help to display a page post parts for editor **/
namespace Pure\Widget\Post;

abstract class Single extends \Pure\Widget {
	public function __construct($args = array(), $args = array()) {
		parent::__construct($args, $args);
		$post_ID = $this->args()->get('post_ID', false);
		if(false == $post_ID) {
			global $post;
			$this->args()->set('post_ID', $post->ID);
		}
	}	

	public function get_simple_edit_buttons() {
		$post_ID = $this->args()->get('post_ID');
		return array(
			array(
				'icon' => 'edit',
				'title' => __('Edit post'),
				'link' => admin_url("post.php?post={$post_ID}&action=edit")
			)
		);
	}
}
