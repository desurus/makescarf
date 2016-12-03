<?php

namespace PureLib\Form\Element;
class Submit extends Element {
	public function _init() {
		$this->set_option('type', 'submit');
		$this->set_option('class', 'btn btn-submit');
	}
	public function render() {
		$html = "<button ";
		$attrs = $this->_build_attribs_string();
		$html .= $attrs;
		$html .= ">";
		$label = $this->get_option('label', 'Submit');
		$html .= "{$label}</button>";
		return $html;
	}
}
