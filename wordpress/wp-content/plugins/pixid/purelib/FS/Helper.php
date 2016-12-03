<?php

namespace PureLib\FS;
class Helper {
	public static function copy_recursive($source_directory, $target_directory) {
		if(!($source_directory instanceof Node\Directory)) $source_directory = new Node\Directory($source_directory);
		if(!($target_directory instanceof Node\Directory)) $target_directory = new Node\Directory($target_directory);
		$nodes = $source_directory->get_child_nodes();
		foreach($nodes as $node) {		
			if(($node instanceof Node\Directory)) {
				//Maybe create a target directory?
				$new_target = $target_directory->maybe_create_directory($node->get_basename());
				self::copy_recursive($node, $new_target);	
			} elseif(($node instanceof Node\File)) {
				$copied = @copy($node->get_fullpath(), self::trailingslashit($target_directory->get_fullpath()) . $node->get_basename());	
			}
		}
		return true;
	}

	/*
	 * FIXME: We need to use our trailingslasit method, not a Wordpress function in PureLib!!!! **/
	public static function trailingslashit($path) {
		return trailingslashit($path);
	}

	public static function get_node_instance($node_path) {
		if(($node_path instanceof Node\Node)) return $node_path;

		if(empty($node_path) || !is_string($node_path)) throw new \Exception("You must provide a valid string.");
		//Try to detect what is this? File or directory?
		if(file_exists($node_path) && is_dir($node_path)) return new Node\Directory($node_path);
		if(file_exists($node_path) && is_file($node_path)) return new Node\File($node_path);
		//FIXME: Here we need to some more stuff I think
		if(!file_exists($node_path)) return new Node\Directory($node_path);
	}
}
