<?php

namespace Pure\Widget\Post;
class Thumbnail extends \Pure\Widget {
	public function widget() {
		$args = $this->args();
		$post_id = $args->get('post_id', false);
		$size = $args->get('size', 'full');
		$attr = $args->get('attr', array());
		if(false == $post_id) {
			global $post;
			$post_id = $post->ID;
		}
		if(empty($post_id)) {
			//return $this->error_box(__("Can not show thumbnail. Can not find a valid post display to."));
			return;
		}
		if(!has_post_thumbnail($post_id)) {
			return;
		}	
		echo get_the_post_thumbnail($post_id, $size, $attr);
	}
}
