<?php

namespace PureLib;

require_once 'Exception.php';
require_once 'Exception/Wordpress.php';

class Utils {
	/**
	 * Just a backward compatible method, because old method has an error in name.
	 * */
	public static function get_images_form_html($html_content) {
		return self::get_images_from_html($html_content);
	}
	/*
	 * This method just returns an array to images SRC parsed from string.
	 * @param string $html_content
	 * @return array
	 * */
	public static function get_images_from_html($html_content) {
		if(empty($html_content) || !is_string($html_content)) return array();
		$dom = new \DOMDocument();
		@$dom->loadHTML($html_content);
		$dom->preserveWhiteSpace = true;
		$images = $dom->getElementsByTagName('img');
		$result = array();
		foreach($images as $img) {
			$result[] = $img->getAttribute('src');
		}
		return $result;
	}
	/*
	 * This method is little different from previous.
	 * This function tries to search a image sources from more elements, not just from <img> tags.
	 * Currently supported tags: a, img
	 * @param string $html_content 
	 * @return array 
	 * */
	public static function get_all_image_urls($html_content) {
		$image_sources = self::get_images_form_html($html_content);
		$links = self::get_all_urls($html_content);	
		$result = array();
		foreach($links as $link) {
			if(self::is_image_url($link)) $result[] = $link;
		}	
		return array_merge($image_sources, $result);
	}

	/*
	 * This function can check if a link is refs to an image resource.
	 * It can check it in two different way. Please, see a details in separated methods:
	 * ::is_image_url_hard()
	 * ::is_image_url_soft() 
	 * TODO: At this moment this method supports check only `soft`-way, just with a parsing of a file extension from url.
	 * */
	public static function is_image_url($url, $hard = false) {
		return self::is_image_url_soft($url);
	}
	/*
	 * This method checks if the url refs to a image resource.
	 * It check only by a file extension in provided url.
	 * @param string $url
	 * @return boolean
	 * */
	public static function is_image_url_soft($url) {
		static $images_extensions = array(
			'jpg', 'jpeg', 'gif', 'png'
		);
		if(empty($url) || !is_string($url)) return false;
		$_ = explode('.', $url);
		$extension = strtolower($_[count($_)-1]);
		if(false !== array_search($extension, $images_extensions)) return true;
		return false;
	}
	/*
	 * This method searches for all urls in A elements in provided content
	 * @param string $html_content
	 * */
	public static function get_all_urls($html_content) {
		if(empty($html_content) || !is_string($html_content)) return array();
		$dom = new \DOMDocument();
		@$dom->loadHTML($html_content);
		$dom->preserveWhiteSpace = true;
		$links = $dom->getElementsByTagName('a');
		$result = array();
		foreach($links as $element) {
			$result[] = $element->getAttribute('href');
		}
		return $result;
	}
	/*
	 * Just loads a wordpress post object by a post_id 
	 * */
	public static function get_post($post_id) {
		if(empty($post_id) || !is_numeric($post_id)) return false;
		return get_post($post_id);
	}
	/*
	 * Just need to check if some url contains a hostname.
	 * FIXME: This function has a very bad code!!!
	 * @param string $url
	 * @return boolean
	 * */
	public static function is_full_url($url) {
		return (false !== strpos($url, 'http://'));
	}
	/*
	 * This method downloads all images from the provided array of urls, and inserts it's as a wordpress attachemnts posts.
	 * @param array $urls
	 * @param string $base_host Base host for urls
	 * @param int $parent_post_id
	 * @return array Array of attachemnt IDs. $result[$url] = $attachment
	 * */
	public static function download_all_images($urls, $base_host = '', $parent_post_id = null) {	  
		if(empty($urls) || !is_array($urls)) return array();
		$result = array();
		foreach($urls as $url) {
			$original_url = $url;
			$id = self::download_single($url, $base_host, $parent_post_id);	
			$result[$original_url] = $id;
		}
		return $result;
	}
	/** 
	 * This method download and insert new media. 
	 * */
	public static function download_single($url, $base_host = '', $parent_post_id = null) {
		if ( !function_exists('media_handle_upload') ) {
			require_once(ABSPATH . "wp-admin" . '/includes/image.php');
			require_once(ABSPATH . "wp-admin" . '/includes/file.php');
			require_once(ABSPATH . "wp-admin" . '/includes/media.php');
		}
		if(!self::is_full_url($url))
			$url = $base_host . $url;
		$_name = urldecode(basename($url));
		$local_file = download_url($url);	
		if(is_wp_error($local_file)) {
			$message = "An critical error occured while download file: {$url}\n";	
			return false;	
		}
		$desc = "{$_name}";
		$file_array = array();
		$file_array['name'] = $_name;
		$file_array['tmp_name'] = $local_file;
		$id = media_handle_sideload( $file_array, $parent_post_id, $desc );
		return $id;	
	}
	/**
	 * This method append some file from provided source as an attachment to a specified post.
	 * @param mixed $file_source The source of attachment, at this moment it can be only a local file path.
	 * @param int $post_id The post id which attachment will be assigned for
	 * @return boolean
	 * */
	public static function append_file_to_post($file_source, $post_id, $args = array()) {
		$defaults = array(
			'desc' => null
		);
		$args = wp_parse_args($args, $defaults);
		if ( !function_exists('media_handle_upload') ) {
			require_once(ABSPATH . "wp-admin" . '/includes/image.php');
			require_once(ABSPATH . "wp-admin" . '/includes/file.php');
			require_once(ABSPATH . "wp-admin" . '/includes/media.php');
		}
		$_name = urldecode(basename($file_source));
		$local_file = $file_source;
		$file_array = array();
		$file_array['name'] = $_name;
		$file_array['tmp_name'] = $file_source;
		if(null == $args['desc']) {
			$args['desc'] = $_name;
		}
		$id = media_handle_sideload($file_array, $post_id, $args['desc']);
		return $id;
	}
	/**
	 * Insert new attachment from the url.
	 * @param string $url
	 * @return int $attachment_id
	 * */
	public static function insert_attachment_from_url($url, $parent_post_id = null) {
		return self::download_single($url, "", $parent_post_id);
	}
	/*
	 * This method, parses post html content and searches it for images urls, if found then download it and insert as a new wordpress attachment. 
	 * Replaces the original post_content with a new links to image resources to a local downloaded copy!
	 * @param int $post_id
	 * @param string $base_host This param required if the urls to images not a full.
	 * @param string $update You can just get an updated content, without any database updates.
	 * @return mixed
	 * */
	public static function download_all_images_post($post_id, $base_host = '', $update = true) {
		$post = self::get_post($post_id);
		$post_content = $post->post_content;
		$post_content = self::download_all_images_content($post_content, $base_host, $update);
		if($update) {
			wp_update_post(array( 'ID' => $post_id, 'post_content' => $post_content ));
		}	
	}
	/**
	 * This method search for all inline image urls, downloads it, and return a replaced content with a local images.
	 * @param string $content
	 * @param string $base_host
	 * @param boolean $update
	 * @return string
	 * */	
	public static function download_all_images_content($content, $base_host = '', $update = true) {
		$urls = self::get_all_image_urls($content);
		$attachments = self::download_all_images($urls, $base_host);
		if(!empty($attachments)) {
			foreach($attachments as $original_url => $attachment_id) {
				$attachment = wp_get_attachment_image_src($attachment_id, 'full');
				$src = $attachment[0];
				$content = str_replace($original_url, $src, $content);
			}
		}
		return $content;
	}
	/**
	 * This method set a post thumbnail just from the url source.
	 * @param int $post_id
	 * @param string $image_url 
	 * @return boolean 
	 * */
	public static function set_post_thumbnail_from_uri($post_id, $image_url) {
		if(empty($post_id) || !is_numeric($post_id)) return false;
		if(empty($image_url) || !is_string($image_url)) return false;
		$attachment_id = self::download_single($image_url, '', $post_id);
		if(!is_numeric($attachment_id)) {
			//generate error
		}
		set_post_thumbnail($post_id, $attachment_id);
	}
	/**
	 * This method create s a taxonomy if it's does not exists.
	 * @param string $name Just a taxonomy name. As in the wp_insert_term function.
	 * @param string $taxonomy The taxonomy type, as in the wp_insert_term function.
	 * @param array $args The args, which just goes raw to wp_insert_term function.
	 * @param boolean $auto_slug If true then the empty 'slug' in args will be populated with a translit method form $name.
	 * @return Object This method alway return false or a valid created or existing term object.
	 * */
	public static function create_term_if_not_exists($name, $taxonomy = 'category', $args = array(), $auto_slug = true) {
		if(!is_string($name) || empty($name)) return false;
		$term = get_term_by('name', $name, $taxonomy);		
		if(empty($term) || ($term instanceof \WP_Error)) {
			if(empty($args['slug']) && true == $auto_slug) {
				$args['slug'] = self::translit($name);
			}
			$data = wp_insert_term($name, $taxonomy, $args);	
			if(($data instanceof \WP_Error)) {
				throw new \PureLib\Exception\Wordpress($data);
			}
			return get_term($data['term_id'], $taxonomy);
		}
		return $term;
	}
	/**
	 * This method is deprecated from version 0.0.21 of this library
	 * @see \PureLib\Urils\Woo::set_product_attributes
	 * */
	public static function woo_set_product_attributes($product_id, $attrs) {
		return \PureLib\Utils\Woo::set_product_attributes($product_id, $attrs);	
	}
	/*
	 * This is a function from cyr2lat plugin! */
	public static function translit($string) {
		if(empty($string) || !is_string($string)) return "";
		global $wpdb;
		$title = $string;
		$iso9_table = array(
			'А' => 'A', 'Б' => 'B', 'В' => 'V', 'Г' => 'G', 'Ѓ' => 'G`',
			'Ґ' => 'G`', 'Д' => 'D', 'Е' => 'E', 'Ё' => 'YO', 'Є' => 'YE',
			'Ж' => 'ZH', 'З' => 'Z', 'Ѕ' => 'Z', 'И' => 'I', 'Й' => 'Y',
			'Ј' => 'J', 'І' => 'I', 'Ї' => 'YI', 'К' => 'K', 'Ќ' => 'K',
			'Л' => 'L', 'Љ' => 'L', 'М' => 'M', 'Н' => 'N', 'Њ' => 'N',
			'О' => 'O', 'П' => 'P', 'Р' => 'R', 'С' => 'S', 'Т' => 'T',
			'У' => 'U', 'Ў' => 'U', 'Ф' => 'F', 'Х' => 'H', 'Ц' => 'TS',
			'Ч' => 'CH', 'Џ' => 'DH', 'Ш' => 'SH', 'Щ' => 'SHH', 'Ъ' => '``',
			'Ы' => 'YI', 'Ь' => '`', 'Э' => 'E`', 'Ю' => 'YU', 'Я' => 'YA',
			'а' => 'a', 'б' => 'b', 'в' => 'v', 'г' => 'g', 'ѓ' => 'g',
			'ґ' => 'g', 'д' => 'd', 'е' => 'e', 'ё' => 'yo', 'є' => 'ye',
			'ж' => 'zh', 'з' => 'z', 'ѕ' => 'z', 'и' => 'i', 'й' => 'y',
			'ј' => 'j', 'і' => 'i', 'ї' => 'yi', 'к' => 'k', 'ќ' => 'k',
			'л' => 'l', 'љ' => 'l', 'м' => 'm', 'н' => 'n', 'њ' => 'n',
			'о' => 'o', 'п' => 'p', 'р' => 'r', 'с' => 's', 'т' => 't',
			'у' => 'u', 'ў' => 'u', 'ф' => 'f', 'х' => 'h', 'ц' => 'ts',
			'ч' => 'ch', 'џ' => 'dh', 'ш' => 'sh', 'щ' => 'shh', 'ь' => '',
			'ы' => 'yi', 'ъ' => "'", 'э' => 'e`', 'ю' => 'yu', 'я' => 'ya'
		);	

		$term = $wpdb->get_var("SELECT slug FROM {$wpdb->terms} WHERE name = '$title'");
		if ( empty($term) ) {
			$title = strtr($title, apply_filters('ctl_table', $iso9_table));
			$title = preg_replace("/[^A-Za-z0-9`'_\-\.]/", '-', $title);
		} else {
			$title = $term;
		}

		return $title;
	}
	
	/*
	 * This fmethod just used to throw exeptions if someone tries to use a woocommerce functions in non-woocommerce environment.
	 * **/
	public static function require_woocommerce() {
		if(!function_exists('WC')) throw new \Exception("Can not use woocommerce functions. Can not find WC() function.");
		$woo = WC();
		if(!is_object($woo) || !($woo instanceof \WooCommerce)) throw new \Exception("Can not use woocommerce function. WC() function must return a valid instance of WooCommerce");
		return true;
	}
	/**
	 * Check if some product exists in current user-session cart. 
	 * @param mixed $product Some product identifier, as ID ot a full WC_Product_Simple object.
	 * @return boolean
	 * */
	public static function is_product_in_cart($product) {
		self::require_woocommerce();
		$w = WC();
		$cart_items = $w->cart->get_cart();
		if(empty($cart_items)) return false;
		if(empty($product)) return false;
		$product_id = $product;
		if(is_object($product)) {
			if($product instanceof \WC_Product_Simple)	$product_id = $product->id;
		}
		foreach($cart_items as $item) {
			
			if($item["product_id"] == $product_id) return true;
		}
		return false;
	}
	public static function get_product_by_id($product_id) {
		static $factory;
		if(!is_object($factory))
			$factory = new \WC_Product_Factory();
		$product = $factory->get_product($product_id);
		return $product;
	}	
}
