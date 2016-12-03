<?php
namespace Pure\Widget\Term\Description;
class Widget extends \Pure\Widget {
	public function get_target_term() {
		$queried_object = get_queried_object();
		$term = null;
		if(($queried_object instanceof \WP_Term)) $term = $queried_object;
		return $term;
	}
	public function widget() {
		$term = $this->get_target_term();	

		if(!($term instanceof \WP_Term)) {
			return $this->error_box("Can not find current term in loop. Please, specify required 'term_id' source argument.");
		}	
		echo $term->description;
	}
	public function get_simple_edit_buttons() {
		$target_term = $this->get_target_term();
		if(!is_object($target_term)) return array();
		return array(
			new \Pure\Editor\Button(array(
				'title' => 'Edit',
				'icon' => 'edit',
				'link' => admin_url('edit-tags.php') . "?action=edit&taxonomy={$target_term->taxonomy}&tag_ID={$target_term->term_id}"
			))	
		);
	}
}
