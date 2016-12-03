<?php

namespace Pure\Helper;
class FS {
	/**
	 * FIXME: This method and a class at all can be more optimized and functional.
	 * @return array REturn an array with full pathes to created directories!
	 * */
	public static function maybe_create_path_recursive($path, $force_document_root = true) {
		if(!self::has_server_root($path)) {
			throw new \Exception("Some internal error occured. You try to work with directory structure not in DOCUMENT_ROOT it's restricted by default.");
		}
		$start_path = "";
		if(self::has_server_root($path)) {
			$start_path = $_SERVER['DOCUMENT_ROOT'];
			$path = str_replace($_SERVER['DOCUMENT_ROOT'], '', $path);
		}
		$directories = explode("/", $path);
		$current_path = $start_path;
		$created_pathes = array();
		foreach($directories as $directory) {
			$root = $current_path;
			$current_path = trailingslashit($current_path) . $directory;
			if(!is_dir($current_path)) {
				if(!is_writable($root)) {
					$message = "Seems target directory ``{$current_path}`` can not be created, because parent directory ``{$root}`` is not writable by server.";
					throw new \Pure\Exception($message);
				}
				$created = @mkdir($current_path);
				if(!$created) {
					$message = "Target directory ``{$current_path}`` can not be created. Uknown error!";
					throw new \Pure\Exception($message);
				}
				$created_pathes[] = $current_path;
			}	
		}
		return $created_pathes;	
	}

	public static function has_server_root($path) {
		return (false !== strpos($path, $_SERVER['DOCUMENT_ROOT']));
	}
}
