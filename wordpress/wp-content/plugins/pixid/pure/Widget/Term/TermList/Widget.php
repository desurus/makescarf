<?php
/**
 * Widget used to retrieve and display a terms list
 * @author Shell
 * @version 0.0.23
 * From version 0.0.22:
 * 	Added some term variables setup directly from widget, such as a some term can be "selected" in template. We have a terms lopping in the code here.
 * */
namespace Pure\Widget\Term\TermList;
class Widget extends \Pure\Widget {
	public function widget() {
		$args = $this->args()->get(array(
			'taxonomy',
			'hide_empty',
			'orderby',
			'order',
			'include',
			'exclude',
			'exclude_tree',
			'number',
			'offset',
			'name',
			'slug',
			'hierarchical',
			'search',
			'name__like',
			'get',
			'child_of',
			'parent',
			'meta_query',
			'meta_key',
			'meta_value'
		));
		
		$terms = get_terms($args);
		//From version 0.0.22 we set the "selected" property here, instead of set this data in templates.
		//From version 0.0.23 Current term ID can be provided via argument variable
		if(!empty($terms)) {
			$current_term = get_queried_object();
			if(!($current_term instanceof \WP_Term)) {
				if($this->args()->get('current_term_id')) {
					$current_term = get_term_by('id', $this->args()->get('current_term_id'), $this->args()->get('taxonomy'));
				}	
			}
			if(($current_term instanceof \WP_Term) && $current_term->taxonomy == $this->args()->get('taxonomy')) {
				foreach($terms as $key => $term) {
					//FIXME: Here we need to loop for all parents!
					if(($term->term_id == $current_term->term_id) || ($term->term_id == $current_term->parent)) {
						$term->selected = true;
					} else {
						$term->selected = false;
					}
				}	
			}
		}	
		$this->display_template(compact('terms'));
	}
}
