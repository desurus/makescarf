<?php

namespace Pure\Widget\Search\Input;
class Widget extends \Pure\Widget {
	public function widget() {
		$search_query = esc_html(get_query_var('s'));
		$this->display_template(compact('search_query'));
	}
}
