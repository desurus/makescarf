<?php

namespace PureLib\Exception;

class Wordpress extends \PureLib\Exception {
	public function __construct($wp_error) {
		var_dump($wp_error);
	}
}
