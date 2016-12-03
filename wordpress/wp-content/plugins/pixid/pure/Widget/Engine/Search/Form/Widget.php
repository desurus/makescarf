<?php

namespace Pure\Widget\Engine\Search\Form;

class Widget extends \Pure\Widget {
	public function widget() {
		echo get_search_form($this->args());
	}
}
