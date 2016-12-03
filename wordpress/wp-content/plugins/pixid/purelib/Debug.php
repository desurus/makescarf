<?php

namespace PureLib;

class Debug {
	/***
	 * This method just dumps all args to a string via var_dump function.
	 * */
	public static function _dump() {
		$html = "";
		ob_start();
		call_user_func_array('var_dump', func_get_args());
		$html = ob_get_clean();
		return $html;
	}
	public static function dump() {
		echo "<pre>";
		$content = "";
		ob_start();
		call_user_func_array('var_dump', func_get_args());
		$content = ob_get_clean();
		$content = htmlspecialchars($content);
		echo $content;
		echo "</pre>";
	}
}
