<?php

namespace Pure\Helper;
class Utils {
	public static function base_classname($classname) {
		$_ = explode('\\', $classname);
		if(empty($_) || count($_) == 1) return $classname;
		return $_[count($_)-1];
	} 
}
