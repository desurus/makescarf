<?php

namespace PureLib\Form\Element;

class Select extends Element {
	protected function _init() {
		
	}

	public function get_values() {
		$values = $this->get_option('values');
		if(empty($values)) {
			$values = $this->get_option('multiOptions');
		}
		return $values;
	}
	protected function _render_input() {
		$attribs = $this->_build_attribs_string(array('value', 'type', 'values', 'multiOptions'));
		$values = $this->get_values(); 
		if(empty($values)) {
			//TODO:
		}	
		$_value = $this->get_value();
		$html = "<select {$attribs}>\n";
		foreach($values as $value => $title) {
			$_selected = "";
			if($_value == $value) $_selected = " selected";
			$html .= "<option value=\"{$value}\"{$_selected}>{$title}</option>\n";
		}
		$html .= "</select>\n";
		return $html;
	}
	/**
	 * FIXME: Here we can do an advanced check, if the provided value is in the list of setted options.
	 * ?*/
	public function is_valid()	{
		return parent::is_valid();
	}
}
