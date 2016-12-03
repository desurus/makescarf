<?php
/**
 * This abstract widget class used for any widgets which will be used internally without any public widgets functionality such as template copieing and other features.
 * @version 0.1
 * @author Shell
 * @
 * */
namespace Pure\Widget;

abstract class Internal extends \Pure\Widget {
	public function __construct($args = array(), $defaults = array()) {
		parent::__construct($args, $defaults);
		$this
			->args()
			->set('wrap_widget', false);
	}
}
