<?php

namespace Pure\Widget\Woo\Category;

class Widget extends \Pure\Widget {
	protected function _get_category_object() {
		$category = $this->args()->get('category');
		if(empty($category)) {
			if(is_product_category()) {
				global $wp_query;
				$category = $wp_query->get_queried_object();
			}
		} else {
			//Need TODO:
		}
		if(is_numeric($category)) {
			//TODO
		}
		if(!is_object($category)) {
			//Something goes wrong. TODO: ???
			return;
		}	
		$category->thumbnail_id = $thumbnail_id = get_woocommerce_term_meta( $category->term_id, 'thumbnail_id', true );
		return $category;
	}
	public function widget() {
		$category = $this->_get_category_object();	
		$this->display_template(array('category' => $category));
	}

	public function get_simple_edit_buttons() {
		$category = $this->_get_category_object();
		return array(
			new \Pure\Editor\Button(array(
				'icon' => 'edit',
				'link' => admin_url("edit-tags.php?action=edit&taxonomy=product_cat&tag_ID={$category->term_id}&post_type=product"),
				'title' => 'Edit this category'
			))
		);
	}
}
