<?php

namespace Pure\Widget\Comments\Form;
class Widget extends \Pure\Widget {
	public function widget() {
		$comment_post_id = $this->args()->get('post_id');
		if(!$comment_post_id) {
			global $post;
			if(is_object($post)) $comment_post_id = $post->ID;
		}
		$errors = $messages = array();
		if(PM()->request()->is_post_request()) {
			$data = PM()->request()->get_post();
			$comment_author = $data->get('comment_author', '');
			$comment_content = $data->get('comment_content', '');
			if(empty($comment_post_id))
				$comment_post_id = $data->get('comment_post_id', '');
			if(empty($comment_author)) {
				$errors[] = "Вы не ввели свое имя. Представьтесь пожалуйста.";
			}	
			if(empty($comment_content)) {
				$errors[] = "Вы не ввели комментарий.";
			}
			$post = get_post($comment_post_id);
			if(!is_object($post)) {
				$errors[] = __("Can not find a target comment post_id. Something goes wrong. Can not add a comment. Check comment_post_id hidden field in your comment form.");
			}
			if(empty($errors)) {
				$comment_data = array(
					'comment_post_ID' => $comment_post_id,
					'comment_author' => $comment_author,
					'comment_content' => $comment_content,
					'comment_approved' => 0
				);			
				$comment_id = wp_insert_comment($comment_data);	
				if($comment_id) {
					$messages[] = "Ваш отзыв добавлен, и будет опубликован после проверки модератором.";
				}
			}

		}
		$this->display_template(compact('errors', 'comment_post_id', 'messages'));	
	}
}
