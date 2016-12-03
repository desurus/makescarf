<?php
/**
 * Basic class for lot of themes, it has a basic functionality and useful functions.
 * Just need to extend this class for your theme class. Offcoure if you use classes.
 * But there are and some static useful functions. Contact me for more info <rei@thindev.co.cm>
 * @author Rei
 * @version 0.1
 * @license GNU General Public License v2 or later
 */
class TDWT_Class {
	public $args;
	public $options;
	/**
	 * Just run init functions in constructor.
	 */
	public function __construct($args = array(), $options = array()) {
		$this->options = $options;
		$defaults = array(
			'DEBUG' => false,
			'PARTS_DIRECTORY' => get_template_directory().'/parts/',
			'POST_THUMBNAILS_SUPPORT' => true	
			);
		$this->args = wp_parse_args($args, $defaults);
		$this->ParseArgs();
		$this->Init();
	}
	public function GetArg($arg, $default = null) {
		if(!isset($this->args[$arg]))
			return $default;
		return $this->args[$arg];
	}
	/**
	 * Here we do some stuff, like enable debug.
	 * Add some basic functionality like all themes has.
	 */
	public function ParseArgs() {
		if($this->GetArg('DEBUG', false))
			self::EnableDebug();
		if($this->GetArg('POST_THUMBNAILS_SUPPORT', true))
			add_theme_support('post-thumbnails');		
	}

	public function Init() {
		
	}
	public function DisplayTemplatePart($_template, $_data = array()) {
		echo $this->FetchTemplatePart($_template, $_data);
	} 
	public function FetchTemplatePart($_template, $_data = array()) {
		$parts_directory = $this->GetArg('PARTS_DIRECTORY');
		$template = sanitize_file_name($_template);
		if(!file_exists($parts_directory.$_template))
			return trigger_error("Can not find `{$template}` part of template in `{$parts_directory}`.", E_USER_WARNING);
		extract($_data, EXTR_SKIP);
		ob_start();	
		include $parts_directory.$template;
		return ob_get_clean();
	}
	public function TheContent($length, $post = null) {
		if(null == $post)
			global $post;
		$content = strip_tags($post->post_content);
		$content = mb_substr($content, 0, $length);
		echo $content;
	}
	public function CreateUserIfNotExists($user_email, $notify = true, $password = null) {
		if(empty($user_email)) return false;
		$user = get_user_by('email', $user_email);
		if(empty($user)) {
			if(null == $password)
				$password = wp_generate_password(5, false);
			$id = wp_insert_user($user_email, $password, $user_email);
			if(false != $notify)
				wp_new_user_notification($id, $password);
			return $id;
		} else {
			return $user->ID;
		}
	}
	/**
	 * Some useful static functions...
	 * */
	public static function ShowWpTitle() {
		global $page, $paged;


		// Add the blog name.
		bloginfo( 'name' );

		// Add the blog description for the home/front page.
		$site_description = get_bloginfo( 'description', 'display' );
		if ( $site_description && ( is_home() || is_front_page() ) )
			echo " | $site_description";

		// Add a page number if necessary:
		if ( $paged >= 2 || $page >= 2 )
			echo ' | ' . sprintf( __( 'Page %s', 'twentyten' ), max( $paged, $page ) );wp_title( '|', true, 'right' );
	}
	public static function IsPostRequest() {
		return ('POST' == $_SERVER['REQUEST_METHOD']);
	}
	public static function SimpleJson($data, $exit = true) {
		echo json_encode($data);
		if($exit)
			exit();
	}
	public static function JsRedirect($location, $timeout = null, $exit = false) {
		//TODO: Other params needed;
		echo "<script type='text/javascript'>window.location='{$location}'</script>";
	}
	public static function Dump() {
		ob_start();
		call_user_func_array('var_dump', func_get_args());
		return ob_get_clean();
	}	
	/**
	 * Function Enable a full debug,e.q. display all error messages.
	 */
	public static function EnableDebug($level = E_ALL, $db_die = true) {
		ini_set('display_errors', 'On');
		error_reporting($level);
		if($db_die && !defined('DIEONDBERROR'))
			define('DIEONDBERROR', true);
		return true;
	}
	
}
