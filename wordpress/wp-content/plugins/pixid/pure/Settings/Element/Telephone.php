<?php

namespace Pure\Settings\Element;
class Telephone extends \Pure\Settings\Element {
	public function get_html() {
		$classes = $this->get_html_classes();
		$html = "<input 
				type=\"tel\" 
				name=\"{$this->get_name()}\" 
				id=\"{$this->get_id()}\" 
				class=\"{$classes}\" 
				value=\"{$this->get_value()}\" 
				placeholder=\"{$this->get_placeholder()}\" 
				title=\"{$this->get_title()}\" />";
		return $html;
	}	
}
