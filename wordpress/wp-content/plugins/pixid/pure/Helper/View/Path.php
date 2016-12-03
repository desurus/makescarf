<?php

namespace Pure\Helper\View;
class Path extends \PureLib\View\Helper {
	public function get_code($path) {
		$original_path = $path;
		$replaces = array(
			$_SERVER["DOCUMENT_ROOT"] => "[<span class=\"pm_strong\">DOCUMENT_ROOT</span>]"
		);	
		$path = str_replace(array_keys($replaces), array_values($replaces), $path);
		$code = "<span class=\"pm_path\" data-original-path=\"{$original_path}\">{$path}</span>";
		return $code;
	}
}
