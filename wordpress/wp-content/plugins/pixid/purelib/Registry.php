<?php

namespace PureLib;

class Registry {
	protected static $_storage;
	public static function set($name, $data = null) {
		self::$_storage[$name] = $data;
	}
	public static function get($name, $default = null) {
		if(!isset(self::$_storage[$name])) return $default;
		return self::$_storage[$name];
	}
}
