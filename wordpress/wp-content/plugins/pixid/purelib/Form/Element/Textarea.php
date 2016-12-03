<?php

namespace PureLib\Form\Element;
class Textarea extends Element {
	protected function _init() {
		
	}

	protected function _render_input() {
		$value = $this->get_option('value');
		$attribs = $this->_build_attribs_string(array('value'));
		return "<textarea {$attribs}>{$value}</textarea>";
	}
}
