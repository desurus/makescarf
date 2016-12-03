<?php

/*
 * This is a part of temporary widgets, which help to display a page post parts for editor **/
namespace Pure\Widget\Post;

class Title extends Single {
	
	public function widget() {
		global $post;
		if(!is_object($post)) {
			//What we need todo?
		}	
		the_title();
	}	
}
