<?php

namespace Pure;

class Debug {
	/***
	 * This method just dumps all args to a string via var_dump function.
	 * */
	public static function _dump() {
		$html = "";
		ob_start();
		call_user_func_array(array('Debug', 'dump'), func_get_args());
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

	/**
	 * This method used in internal calls, it just trigger an deprecated notices of usign deprecated methods in WAMT API.
	 * @param string $method Method which is deprecated and triggered this message.
	 * @param string $use The new method name which can be used to provide the same functinality.
	 * @param string $from_version Optional version number when the method was deprecated.
	 * @return null The result of this method is useless and can not be used.
	 */
	public static function deprecated_method($method, $use, $from_version = null) {
		$message = "Method `{$method}` is deprecated in WAMT API. Please use `{$use}` instead.";
		if(is_string($from_version)) {
			$message .= " Method was deprecated from version {$version}.";
		}
		$message .= " Method will be deleted in new versions.";
		return trigger_error($message, E_USER_DEPRECATED);
	}
}
