<?php

namespace Pure\Widget\Posts\Single;

class Widget extends \Pure\Widget {
	public function get_post() {
		$post_id = $this->args()->get('post_id', false);
		return get_post($post_id);
	}
	public function widget() {
		$post = $this->get_post();
		if(empty($post)) return;
		$this->display_template(compact('post'));
	}

	public function get_simple_edit_buttons() {
		$post = $this->get_post();
		if(!$post) return array();
		$post_id = $post->ID;
		return array(
			new \Pure\Editor\Button(array(
				'title' => 'edit',
				'icon' => 'edit',
				'link' => admin_url("post.php?post={$post_id}&action=edit")
			))
		);
	}
}
