<?php
/**
 * This class used for image resize methods.
 * @author Shell
 * @version 0.1
 * TODO: Need to throw exception when some error is occured!
 * */
namespace PureLib;
class Image {
	protected $_filepath;
	protected $_handle;

	public function __construct($filepath = null) {
		$this->_filepath = $filepath;
	}
	/**
	 * This method gets a image handle if fullpath provided and image not opened yet.
	 * @return resource
	 * */
	public function get_handle() {
		if(!is_resource($this->_handle)) {
			if(empty($this->_filepath)) {
				throw new \Exception("Image can not be opened, because source file not specified. Please set the handle directly or set filepath.");
			}
			//TODO: Check if we can read a file!
			$this->_handle = imagecreatefromstring(file_get_contents($this->_filepath));
		}
		return $this->_handle;
	}
	/**
	 * Just a setter for set a direct handle for this image.
	 * @param mixed $handle The image handle or null?
	 * */
	public function set_handle($handle) {
		$this->_handle = $handle;
	}
	/*
	 * This method resizes iamges proportionally to max width and height params.
	 * @param int $max_width
	 * @param int $max_height
	 * @return \PureLib\Image return a new instance of this class. Which can be used directly from original.
	 * TODO: Thsi method must have a more errors checking methods.
	 *
	 * **/
	public function resize_max($max_width, $max_height) {
		if(empty($this->_filepath)) return false;
		$original_sizes = getimagesize($this->_filepath);
		if(empty($original_sizes)) return false;
		$width = $original_sizes[0];
		$height = $original_sizes[1];
		$scale = $width / $height; // width/height
		if( $scale > 1) {
			$new_width = $max_width;
			$new_height = $max_width / $scale;
		}
		else {
			$new_width = $max_height * $scale;
			$new_height = $max_height;
		}
		$new_width = intval($new_width);
		$new_height = intval($new_height);
		$handle = $this->get_handle();
		//Open target image
		$destination = imagecreatetruecolor($new_width, $new_height);
		imagecopyresampled($destination, $handle, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
		$new = new self();
		$new->set_handle($destination);
		return $new;
	}
	/**
	 * This method just save opened handle to a file.
	 * @param string $filepath A path to a file where this handle will be saved
	 * TODO: We need to parse an extension and use different imageX functions to save images.
	 * TODO: We can provide more arguments to target imageX functions.
	 * */
	public function save_to($filepath) {
		//TODO: Check filepath is writable...
		//TODO: Check if handle is opened
		if(stripos($filepath, '.jpg') || stripos($filepath, '.jpeg')) {
			$result = imagejpeg($this->_handle, $filepath);
		} elseif(stripos($filepath, '.png')) {
			$result = imagepng($this->_handle, $filepath);
		} else {
			throw \Exception("Unknown file type {$filepath}. Can not get a file type from extension. Please provide a valid .jpg or .png extension to file path.");
		}
		return $this;
	}
	public function __destruct() {
		if(is_resource($this->_handle)) {
			imagedestroy($this->_handle);
		}
	}


}
