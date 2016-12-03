<?php
namespace MakeScarf\Widget\ScarfPreview;

class Widget extends \Pure\Widget {
	public function widget() {
		$source_id = $this->args()->get('post_id', false);
		if(empty($source_id) || !is_numeric($source_id)) return $this->error_box(__("Can not show a scarf preview. No source post ID specified!"));
		$scarf = PM()->Woo()
		       		->get_product_by_id($source_id);
		$this->display_template(compact('scarf'));	
	}
}
