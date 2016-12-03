<?php

namespace PureLib\Form\Element;
class Hidden extends Element {
	protected function _init() {
		$this->set_option('type', 'hidden');	
	}
}
