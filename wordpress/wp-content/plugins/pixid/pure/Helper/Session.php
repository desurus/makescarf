<?php

namespace Pure\Helper;
class Session {
	
	public static function store($name, $value) {
		\Pure\Session::instance()->set($name, $value);	
		return true;
	}
	public static function get($name, $default = null) {
		return \Pure\Session::instance()->get($name, $default);	
	}
}
