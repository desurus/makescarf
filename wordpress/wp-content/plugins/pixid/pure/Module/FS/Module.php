<?php

namespace Pure\Module\FS;

class Module extends \Pure\Module {
	protected function _init() {
	
	}
	/**
	 * Triggers an new file for any FS node...
	 * */
	public function track_new_nodes($nodes) {
		$module_settings = $this->settings();	
		if(!$module_settings->get('track_permissions', true)) return true;
		
		$files_perms = octdec($module_settings->get('files_mode', 0755));
		$dirs_perms = octdec($module_settings->get('directories_mode', 0755));	
		if(!empty($nodes)) {
			foreach($nodes as $node) {
				if(!($node instanceof \PureLib\FS\Node\Node)) {
					$node = \PureLib\FS\Helper::get_node_instance($node);
				}
				if(($node instanceof \PureLib\FS\Node\Directory)) {
					$node->chmod($dirs_perms);
				} elseif(($node instanceof \PureLib\FS\Node\File)) {
					$node->chmod($files_perms);
				}	
			}	
		}	
		return true;
	}
	/*
	 * This method just automaticaly gets a directory contents as a FS\Node list and call $this->track_new_nodes 
	 * */
	public function track_new_files($directory) {
		$directory = new \PureLib\FS\Node\Directory($directory);
		$all_nodes = $directory->get_child_nodes_recursive();
		
		return $this->track_new_nodes($all_nodes);	
	}

	public function get_module_name() {
		return 'FS';
	}
}
