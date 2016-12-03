<?php
/**
 * This class helps to work with a files.
 * At this moment this is just a wrapper for main PHP functions such as file_get_contents/file_put_contents.
 * TODO: More better and extended class for work with files.
 * @version 0.1
 * @author Shell
 * @
 * */
namespace PureLib\FS\Node;
class File extends Node {
	protected $_open_mode;

	public function get_file_content() {
		return file_get_contents($this->get_fullpath());
	}	
	public function put_file_content($content) {
		return file_put_contents($this->get_fullpath(), $content);
	}
}
