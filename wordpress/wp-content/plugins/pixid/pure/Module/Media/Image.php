<?php

/**
 * This is a base class for nice and extended image manipulation in Wordpress.
 * @version 0.1
 * @author Shell
 * FIXME: Need to check the file in a public directory (maybe in the wp-upload dir!)
 * */
namespace Pure\Module\Media;
class Image {
	protected $_image_path;
	protected $_wp_image_editor;

	public function __construct($image_path) {
		if(empty($image_path) || !is_string($image_path)) throw new \Pure\Exception("Image path must be a valid string.");
		if(!file_exists($image_path) || !is_readable($image_path)) throw new \Pure\Exception("Source image file `{$image_path}` is not readable.");
		$this->_image_path = $image_path;
	} 

	public function get_editor() {
		if(!is_object($this->_wp_image_editor)) {
			$this->_wp_image_editor = wp_get_image_editor($this->_image_path);
		}
		return $this->_wp_image_editor;
	}


	public function get_image_src($width = null, $height = null, $crop = true) {
		$editor = $this->get_editor();	
		if(null == $width) {
			return $this->path_to_public($editor->get_file());
		}
		$in_editor_size = $editor->get_size();
		if($in_editor_size["width"] > $width) {	
			$in_editor_size = $editor->get_size();
			$basedir = dirname($this->_image_path);
			$basename = basename($this->_image_path);
			$_ = explode('.', $basename);
			$extension = array_pop($_);	
			//$crop_str = (string) $crop;
			if($crop)  {
				$crop_str = "true";
			} else {
				$crop_str = "false";
			}
			$new_file = implode('.', $_) . "_{$width}x{$height}_{$crop_str}.{$extension}";
			$new_file = trailingslashit($basedir) . $new_file;	
			if(file_exists($new_file)) {
				return $this->path_to_public($new_file);
			}
			$editor->resize($width, $height, $crop);
			$result = $editor->save($new_file);
			//var_dump($result); die();
			return $this->path_to_public($new_file);
		}	
		return $this->path_to_public($this->_image_path);	
	}

	public function path_to_public($path) {
		$upload = wp_upload_dir();
		
		$replaces = array(
			$upload['basedir'] => $upload['baseurl']			
		);
		return str_replace(array_keys($replaces), array_values($replaces), $path);
	}

	public static function from_thumbnail($thumbnail) {
		if(is_numeric($thumbnail)) {
			$thumbnail = get_post($thumbnail);
		}
		if(!is_object($thumbnail)) {
			throw new \Pure\Exception("You must provide a valid thumbnail source. Object or ID required.");
		}
		if($thumbnail->post_type != 'attachment') {
			throw new \Pure\Exception("Thumbnail not a valid attachment.");
		}

		$attached_file = get_attached_file($thumbnail->ID);
		return new self($attached_file);
	}
}
