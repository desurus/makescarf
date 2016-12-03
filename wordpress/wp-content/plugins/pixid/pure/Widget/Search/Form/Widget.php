<?php
namespace Pure\Widget\Search\Form;
class Widget extends \Pure\Widget {
	public function widget() {
		$post_type = $this->args()->get('post_type', false);
		if(!$post_type) {
			$post_type = $this->request()->get('post_type');
		}
		$current_search_term = get_query_var('s');
		$this->display_template(compact('current_search_term'));	
	}
}
