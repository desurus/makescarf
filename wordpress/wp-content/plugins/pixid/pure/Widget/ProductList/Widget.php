<?php

namespace Pure\Widget\ProductList;
/*
 * TODO: At this moment this widget has a very basic functionality, but it may be lot functional in the near feature, we need to extends IT!
 * */
class Widget extends \Pure\Widget\Posts\PostsList\Widget {
	public function get_default_posts_args() {
		$args = parent::get_default_posts_args();
		$args['post_type'] = 'product';
		return $args;
	}

	public function get_simple_edit_buttons() {
		return array(
			new \Pure\Editor\Button(array(
				'title' => 'Products list',
				'link' => 'javascript:void();',
				'icon' => 'edit'	
			))
		);
	}
}
