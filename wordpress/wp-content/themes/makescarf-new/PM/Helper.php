<?php

namespace MakeScarf;
class Helper {
	public static function get_scarf($scarf_id) {
		$scarf = get_post($scarf_id);
		if(is_object($scarf)) {
			self::load_scarf_data($scarf);
		}
		return $scarf;
	}
	public static function load_scarf_data(\WP_Post $scarf) {
		
	}
}
