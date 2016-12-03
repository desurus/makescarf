<?php

namespace PureLib\Config;

class RawArray extends \PureLib\Storage {	
	public static function from_json($file) {
		if(!file_exists($file)) {
			//TODO:
		}	
		$data = file_get_contents($file);
		$data = json_decode($data, true);
		if(!is_array($data)) $data = array();
		return new self($data, array());
	}
}
