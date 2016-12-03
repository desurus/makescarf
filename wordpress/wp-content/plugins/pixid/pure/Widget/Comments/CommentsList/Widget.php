<?php

namespace Pure\Widget\Comments\CommentsList;
class Widget extends \Pure\Widget {
	public function widget() {
		$args = $this->args();
		//Get a comments data-source
		$post_id = $args->get('post_id', false);
		if(!$post_id) {
			global $post;
			$post_id = $post->ID;
		}
		$paged = get_query_var('paged');
		global $wpdb;
		$total_comments = $wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->comments} WHERE (comment_post_ID = {$post_id}) AND (comment_approved = 1)");	
		$number = $args->get('number', 5);
		$offset = 0;
		if($paged > 1) {
			$offset = ($paged - 1) * $number;	
		}
		$comments = get_comments(array(
			'post_id' => $post_id,
			'status' => $args->get('status', 'approve'),
			'number' => $args->get('number', 5),
			'offset' => $offset
		));
		//Get a total comments which available for this post
			
		$display_comments_count = count($comments);

		$this->display_template(compact('comments', 'total_comments', 'display_comments_count'));
	}
}
