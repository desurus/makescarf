<?php
/**
 * This class provide a more or less flexible methods to create a valid select inputs in forms.
 * @author Shell
 * @version 0.0.21
 * Changelog:
 * 	0.0.21: 
 * 		Added a lot of fixes. Now select have a basic support of grouped select inputs.
 *
 * */
namespace PureLib\Form\Element;

class SelectNG extends Element {
	public function get_default_args() {
		return array(
			'auto_multi_name' => true
		);	
	}
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
		$attribs = $this->_build_attribs_string(array('value', 'type', 'values', 'multiOptions', 'grouped'));
		$values = $this->get_values(); 
		if(empty($values)) {
			//TODO:
		}	
		$_value = $this->get_value();
		$html = "<select {$attribs}>\n";

		foreach($values as $value => $title) {
			
			if(is_array($title)) {
				$group_selected = false;
				
				if(in_array($value, $_value)) $group_selected = true;
				
				$html .= "<optgroup label=\"{$title['label']}\">\n";
				$values = @$title['items'];
				if(empty($values)) {
					$values = @$title['values'];
				}
				foreach($values as $value => $item) {
					$_selected = "";
					if($group_selected) $_selected = " selected";
					if(in_array($item, $_value))	$_selected = " selected";
					$title = $item;
//					$value = $item;
					$html .= "<option value=\"{$value}\"{$_selected}>{$title}</option>\n";
				}
				$html .= "</optgroup>\n";
				continue;
			}	
			$_selected = "";
			if(!is_array($_value)) {
				if(null != $_value && $_value == $value) $_selected = " selected";
			} else {
				if(in_array($value, $_value)) $_selected = " selected";
			}
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
