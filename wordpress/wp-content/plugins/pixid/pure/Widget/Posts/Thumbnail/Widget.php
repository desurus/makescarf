<?php

namespace Pure\Widget\Posts\Thumbnail;

class Widget extends \Pure\Widget {
	public function get_default_args() {
		return array(
			'img_class' => 'post-thumbnail',
			'alt' => '',
			'image_empty' => false,
			'size' => 'full',
			'title' => null,
			'class' => null
		);
	}
	public function widget() {
		$args = $this->get_args();
		$defaults = $this->get_default_args(); 
		if($args->get('post_id')) {
			$post = get_post($args->get('post_id'));
		} else {
			$post = $args->get('post', null);
		}
		if(null == $post) {
			global $post;
		}
		if(!is_object($post)) {
			//Seems some error occured...
			$this->maybe_display_error("Can not find a valid post object to display a thumbnail.");
			return;
		}
		$params = new \PureLib\Config\RawArray($args, $defaults);

		if(!$params->get('alt')) $params->set('alt', $post->post_title);
		if(null == $params->get('title')) $params->set('title', $post->post_title);
		if($post->post_type == 'attachment')
			$post_thumbnail_id = $post->ID;
		else {
			$post_thumbnail_id = get_post_thumbnail_id($post->ID);
		}

		if(empty($post_thumbnail_id))	{ return; }//TODO: Maybe we need to display noimage?
		$size = $params->get('size');
		if(is_array($size)) {
			//We work with really custom size..
			$image_src = PM()->Media()->get_resized_image_src($post_thumbnail_id, $size, true);
		} else {
			$attachment = wp_get_attachment_image_src($post_thumbnail_id, $size);
			$image_src = $attachment[0];
		}

		$this->display_template(array(
			'attachment' => $attachment,
			'image_src' => $image_src,
			'params' => $params,
			'args' => $args,
			'post' => $post,
			'post_thumbnail_id' => $post_thumbnail_id,
			'alt' => $params->get('alt'),
			'title' => $params->get('title'),
			'class' => $params->get('class')
		));
	}

	public function get_internal_buttons() {
		return array();
	}
}
