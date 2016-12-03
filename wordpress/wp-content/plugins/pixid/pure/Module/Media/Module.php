<?php

/**
 * This module provides an overall usefull method to manipulate with media files with Wordpress.
 * @version 0.1
 * @author Shell
 * @
 * */
namespace Pure\Module\Media;
class Module extends \Pure\Module {
	public function _init() {
	
	}
	public function get_image_src($image) {
		$file = $thumbnail = null;
		if(is_numeric($image)) {
			$thumbnail = wp_get_attachment_image_src($image, 'full');
		}
		return $thumbnail[0];	
	} 
	/**
	 * This method get's an image from different locations and return an url to a resized copy of the image, or empty value!
	 * @param string $image A mixed image source. It can be only: Local file path, Thumbnail ID. 
	 * @param array $size Needed sizes. An array with keys array(0|width => Image width, 1|height => Image height)
	 * @param boolean $crop Crop this image? Just follow this param to an internal library and WP_Image_Editor class.
	 * @return string The resized url.
	 * */
	public function get_resized_image_src($image, $size = null, $crop = true) {
		$file = null;
		if(is_numeric($image)) {
			$file = get_attached_file($image);
		}
		$url = ""; 
		if(empty($size[0]) && !empty($size['width'])) {
			$size[0] = $size['width'];
		}
		if(empty($size[1]) && !empty($size['height'])) {
			$size['1'] = $size['height'];
		}
		if(!empty($file)) {
			$_image = new Image($file);
			$url = $_image->get_image_src($size[0], $size[1], $crop);	
		}
		if(!empty($url)) return $url;
		return "";
	}
}

