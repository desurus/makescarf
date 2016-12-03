<?php

namespace Pure\Helper;

class Template {
	public static function copy_template($source_template, $target_template) {
		if(!($source_template instanceof \Pure\Template\Template)) throw new \Exception("Can not copy template. Source template must be a valid object.");
		if(!($target_template instanceof \Pure\Template\Template)) throw new \Exception("Can not copy template. Target template must be a valid object.");
		if(!$source_template->exists()) throw new \Exception("Source template not exists. You must provide a valid existing source template.");
		try {
			$created_pathes = \Pure\Helper\FS::maybe_create_path_recursive($target_template->get_template_directory());
			//FIXME: This is a temporary solution, this method at all must return some other data?
			if(!empty($created_pathes)) {
				PM()->module("FS")->track_new_nodes($created_pathes);
			}
		} catch(\Exception $e) {
			throw $e;
		}
		$source_directory = $source_template->get_template_directory();
		\PureLib\FS\Helper::copy_recursive($source_directory, $target_template->get_template_directory());
	}
}
