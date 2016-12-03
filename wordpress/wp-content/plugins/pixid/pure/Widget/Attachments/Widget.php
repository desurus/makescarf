<?php

/*
 * This widget used for working and display a post attachments with plugin: https://github.com/jchristopher/attachments 
 * */

namespace Pure\Widget\Attachments;
class Widget extends \Pure\Widget {
	public function widget() {
		$post = \Pure\Helper\Wordpress::get_current_post($this->args()->get('post', null));
		if(!is_object($post)) {
			return $this->error_box(__("Can not display or get any attachments, because a global post object not provided. You are not in the loop, and 'post' argument not provided or invalid."));
		}
		if(!class_exists('\Attachments', false)) {
			return $this->error_box(__("Seems attachments plugin is not installed, please install attachments plugin to use this widget."));
		}
		$instance_name = $this->args()->get('attachments_instance_name', 'attachments');
		if(empty($instance_name)) {
			//return $this->erro
		}
		$attachments = new \Attachments($instance_name, $post->ID);
		$this->display_template(compact('attachments'));	
	}
	public function get_simple_edit_buttons() {
		$post_id = $this->args()->get('post');
		if(is_object($post_id)) $post_id = $post_id->ID;
		return array(
			new \Pure\Editor\Button(array(
				'title' => 'Edit',
				'icon' => 'edit',
				'link' => admin_url("post.php?post={$post_id}&action=edit")
			))
		);
	} 	
}
