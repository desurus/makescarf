<?php

namespace Pure\Settings\Element;
class Dropdown extends \Pure\Settings\Element {
	public function _parse_args() {
			
	}
	public function get_html() {
		$values = $this->get_args()->get('values');
		$html = "<select name=\"{$this->get_name()}\" id=\"{$this->get_id()}\" class=\"{$this->get_html_classes()}\">\n";
		$_value = $this->get_value();	
		foreach($values as $value => $title) {
			$selected = "";
			if($value == $_value) 
				$selected = " selected";
			$html .= "<option value=\"{$value}\"{$selected}>{$title}</option>\n";
		}
		$html .= "</select>\n";
		return $html;	
	}	
}
