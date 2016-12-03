<?php

namespace Pure\Helper;

class Data {
	public static function encode($data) {
		return base64_encode($data);
	}

	public static function decode($data) {
		return base64_decode($data);
	}
	/**
	 * This method clears the string variables which can be used in a some path building.
	 * */
	public static function clear_path_var($var) {
		$replaces = array(
			'./' => '',
			'../' => '',
			"\0" => ''
		);
		return str_replace(array_keys($replaces), array_values($replaces), $var);
	}
}
