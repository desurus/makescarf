<?php

namespace Pure\Settings\Element;
class Textarea extends \Pure\Settings\Element {
	public function get_html() {
		$classes = $this->get_html_classes();
		$html = "<textarea name=\"{$this->get_name()}\" 
				id=\"{$this->get_id()}\" 
				class=\"{$classes}\" 
				placeholder=\"{$this->get_placeholder()}\" 
				title=\"{$this->get_title()}\">{$this->get_value()}</textarea>";
		return $html;
	}	
}
