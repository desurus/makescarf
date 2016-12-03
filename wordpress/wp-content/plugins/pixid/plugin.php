<?php
/*
Plugin Name: PixiD 
Plugin URI: http://pixid.parked.pw/ 
Description: Плагин содержит в себе хорошо написанный фреймворк с большим количеством готовых функций для разработки сайтов на Wordpress. Они будут полезны не только разработчикам, но и контент-менеджерам. Забудьте о обилии пунктов меню в панеле Wordpress, и о невозможности найти нужные настройки или участок текста. Сайты разработанные на этом фреймворке избавлены от этого недостатка. <strong>Данная версия находится в разработке, и может быть нестабильна.</strong> Воспользуйтесь пунктом меню в панеле, для получения дополнительной информации.
Author: Shell
Version: 0.0.23-dev
Author URI: http://pixid.parked.pw/
*/

define('WAMT_DOMAIN', 'wamt');
define('WAMT_ROOT', __DIR__);
define('WAMT_TEXTDOMAIN', 'wamt');

class PixiD {
	protected $_config;
	protected $_data_directory;
	protected $_autoloader;
	protected $_logger;
	protected $_snippets_root_directories;	
	protected $_request;
	protected $_loaded_modules;
	/**
	 * This method just initialize a basic manager environment and some settings...
	 * @Hooks:
	 * @Filters:
	 * */
	public function __construct() {		
		$this->load_internal_libs();

		$this->_init_modules();	
	}
	/**
	 * This method initializes an autoload modules, and internal required modules.
	 * All other modules can be called directly on usage.
	 * */
	protected function _init_modules() {
		$this->module("Debug");

		$this->template_processor();	
			

		add_action('init', array( $this, 'init' ), 5);
		//We do a hooks to prepend or append a new snippets directories from othe plugins	
		$this->register_hooks();
		
		$this->module("EarlyHooks");
		$this->module("Ajax");
		$this->module("Iframe");
		$this->module("Frontend");

		$this->module("InlineEditor");
		$autoload_modules = $this->settings()->get('autoload_modules', array());
		if(!empty($autoload_modules))	{
			foreach($autoload_modules as $module_name) {
				$this->module($module_name);
			}
		}
	}
	/**
	 * This method used to initialize and get an instance of a \PureLib\Autoloader
	 * @return \PureLib\Autoloader
	 * */
	public function autoloader() {
		if(!is_object($this->_autoloader)) {
			//We provide out PureLib current library...
			require_once 'purelib/Autoloader.php';
			
			//Our classes namespaces for autoloader
			$this->_autoloader = new \PureLib\Autoloader();

			$this->_autoloader->register_namespace('PureLib', __DIR__ . '/purelib/' );
			$this->_autoloader->register_namespace('Pure', __DIR__ . '/pure/' );
			do_action('pixid_autoloader_init', $this->_autoloader);
		}
		return $this->_autoloader;
	}	
	/*
	 * FIXME: This code and a code of TemplateProcessor module initialization.
	 * And module initialization at all need to be modified!
	 * **/
	public function template_processor() {
		static $instance;
		if(!is_object($instance)) {
			$instance = new \Pure\Module\TemplateProcessor\Module($this);
		}
		return $instance;
	}

	public function get_settings_cookie_name() {
		return "_pm_settings";
	}
	public function get_default_settings() {
		return new \PureLib\Config\RawArray(array(
			'editor_enabled' => true
		));
	}
	public function get_settings_from_cookie() {
		$cookie_name = $this->get_settings_cookie_name();
		if(empty($_COOKIE[$cookie_name])) {
			return $this->get_default_settings(); 
		}	
		$cookie_data = @unserialize(base64_decode(($_COOKIE[$cookie_name])));
		if(!is_object($cookie_data) || !($cookie_data instanceof \PureLib\Config)) {
			return $this->get_default_settings();
		}
		return $cookie_data;
	}

	public function set_settings_cookie($settings) {
		$cookie_data = base64_encode(serialize($settings));
		$cookie_name = $this->get_settings_cookie_name();
		setcookie($cookie_name, $cookie_data, 0, "/");
		$_COOKIE[$cookie_name] = $cookie_data;
	}
	
	public function maybe_toggle_editor_state() {
		if(!empty($_REQUEST['pm_enable_editor'])) {
			if($_REQUEST['pm_enable_editor'] == 'false') {
				$settings = $this->get_settings_from_cookie();
				$settings->set('editor_enabled', false);
			} elseif($_REQUEST['pm_enable_editor'] == 'true') {
				$settings = $this->get_settings_from_cookie();
				$settings->set('editor_enabled', true);
			}
			$this->set_settings_cookie($settings);
		}
	}

	public function maybe_enable_debug() {
		if(current_user_can('administrator')) {
			ini_set('display_errors', 'On');
			error_reporting(E_ERROR);
		} else {
			ini_set('display_errors', 'Off');
		}
	}
	
	public function load_internal_libs() {
		$this->autoloader();	
	}

	public function init() {
		do_action('wamt_before_init', $this);	

		$snippets_directories = array(
			get_template_directory(),
				get_template_directory() . '/pure-snippets',
			get_template_directory() . '/snippets',
			$_SERVER['DOCUMENT_ROOT']
		);
		$snippets_directories = apply_filters('wamt_snippets_directories', $snippets_directories);
		foreach($snippets_directories as $snippet_directory) {
			$this->add_snippet_root_directory($snippet_directory);
		}
		//Here we try to automaticaly find the PM Widgets in current theme
		/*$theme_root = get_template_directory();
		$theme_pm_widgets_directory = $theme_root . "/PM/Widget";
		if(is_dir($theme_pm_widgets_directory)) {
			
		}
		
		 */
		//wamt_after_init is deprecated action due to framework renamed.
		do_action('wamt_after_init', $this);
		do_action('pixid_after_init', $this);
		$this->maybe_toggle_editor_state();	
	}

	public function register_hooks() {
		add_action('wp_enqueue_scripts', array( $this, 'include_header_scripts' ));	
		add_action('admin_bar_menu', array( $this, 'admin_bar_menu' ));
		//FIXME: TODO: This is a temporary shortcode, WE NEED TO STORE A REAL PHP CODE IN POSTS??!!
		add_shortcode('pm_widget', array($this, 'widget_shortcode_call'));
		add_shortcode('pm_snippet', array($this, 'snippet_shortcode_call'));
		add_filter('template_include', array($this, 'template_include'), 1, 1);
		
	}

	public function template_include($template) {
		//$template_file_content = file_get_contents($template);
		//TODO: Here we can do something with early header in wordpress...		
		return $template;
	}

	public function widget_shortcode_call($args = array()) {
		if(empty($args['widget'])) {
			return $this->error_box("You must specify a valid widget path, with widget= arg to pm_widget shortcode.", array('echo' => false));	
		}
		$widget = $args['widget'];
		//FIXME: Here we have some interesting moment, the call_from data must be filled from this post data.	
		return $this->fetch_widget($widget, $args);
	}

	public function snippet_shortcode_call($args = array()) {
		if(empty($args['file'])) {
			return $this->error_box(__("You must sprecify ``file`` argument for snippet call!", WAMT_DOMAIN));
		}
		$file = $args['file'];
		return $this->fetch_snippet($file, $args);
	}
	
	public function is_editor_enabled() {
		$settings = $this->get_settings_from_cookie();
		return $settings->get('editor_enabled');	
	}

	public function admin_bar_menu( $wp_admin_bar ) {
		//Prevent display this button while admin panel was requested.
		if(is_admin()) return;
		if(!$this->is_editor_enabled()) {
			$link = add_query_arg(array(
				'pm_enable_editor' => 'true'
			));
			$args = array(
				'id' => 'enable-inline-editor',
				'title' => __("Enable Editor", 'wamt'),
				'href' => $link 
			);
		} else {
			$link = add_query_arg(array(
				'pm_enable_editor' => 'false'
			));
			$args = array(
				'id' => 'disable-inline-editor',
				'title' => __('Disable Editor', 'wamt'),
				'href' => $link
			);
		}
		$wp_admin_bar->add_node($args);
	}
	
	public function register_widgets_ns($namespace, $path) {
		$autoloader = $this->_autoloader;
		$autoloader->register_namespace($namespace, $path);	
		return $this;
	}

	public function late_register_widgets_ns($namespace, $path) {
		$this->_autoloader->register_namespace($namespace, $path);	
	}	

	public function image_editors_list($list) {

	}

	public function include_header_scripts() {
		//Support of late require scripts
		if(!current_user_can('edit_posts'))	return;
		wp_enqueue_script('dashicons');
		//Featherlight very lightweight lightbox alternative...
		wp_enqueue_style('featherlight', plugin_dir_url(__FILE__) . '/js/featherlight/src/featherlight.css');
		wp_enqueue_script('featherlight', plugin_dir_url(__FILE__) . '/js/featherlight/src/featherlight.js' , array( 'jquery' ));
		wp_enqueue_style('pm-inline-editor', plugin_dir_url(__FILE__) . '/css/pm-inline-editor.css');	
		wp_enqueue_script('pm-inline-editor', plugin_dir_url(__FILE__) . '/js/pm-inline-editor.js', array( 'jquery' ));
	}

	public function can_include_editor($widget = null, $args = array()) {	
		if(is_object($widget)) {
			$args = $widget->args()->to_array();
		}
		if(!empty($args['wrap_area']) && false === $args['wrap_area']) return false;	
		if(!empty($args['wrap_widget']) && false === $args['wrap_widget']) return false;

		if(defined('IFRAME_REQUEST') && IFRAME_REQUEST) return false;
		if(\Pure\Helper\Wordpress::in_customizer()) return false;	

		$result = (current_user_can('edit_posts'));
		if(!$result) return $result;//If really user can not edit posts...

		$result = $this->is_editor_enabled();
		//$result = apply_filters('wamt_can_wrap', $result, $snippet, $args);
		
		return (boolean) $result;
	}
	public function wrap_with_editor($code, $args = array()) {
		return $this->worker()->wrap_with_editor($code, $args);	
	}
	public function maybe_wrap_with_editor($code) {
		$this->worker()->maybe_wrap_with_editor($code);	
	}
	public function fetch_snippet($filename, $params = array(), $args = array()) {
		if(empty($args['call_from'])) {
			$backtrace = debug_backtrace();
			$from = $backtrace[0];
			$args['call_from'] = $from; 
		}
		$defaults = array(
			'wrap_area' => true
		);
		$args = wp_parse_args($args, $defaults);	
		if(empty($filename) || !is_string($filename)) {
			return "<p class=\"pm-error\">Not a valid snippet!</p>";
		}
		
		foreach($this->_snippets_root_directories as $directory) {
			$full_path = trailingslashit($directory) . $filename;
			
			if(file_exists($full_path)) {
				$full_path = apply_filters('wamt_snippet_path', $full_path, $params, $args);
				ob_start();
				extract($params, EXTR_SKIP);
				include $full_path;
				$html = ob_get_clean();	
				if($this->can_include_editor($full_path, $args)) {
					if(!$args['wrap_area']) return $html;
					
					$_args = array_merge(array('wrapper_style' => @$params['wrapper_style']), array( 'snippet_path' => $full_path ));
					$html = $this->wrap_with_editor($html, $_args);
				}
				return $html;
			}
		}
		return "Could not find a snippet {$filename} in registered snippet locations!";
	}
	public function display_snippet($filename, $params = array(), $args = array()) {
		if(empty($args['call_from'])) {
			$backtrace = debug_backtrace();
			$from = $backtrace[0];
			$args['call_from'] = $from; 
		}
		echo $this->fetch_snippet($filename, $params, $args);
	}	
	/**
	 * We do not wrap a widgets at this moment with any editors.*/
	public function display_widget($name, $args = array()) {
		if(empty($args['call_from'])) {
			$backtrace = debug_backtrace();
			$from = $backtrace[0];
			$args['call_from'] = $from;
		}

		return $this->worker()->display_widget($name, $args);
	}
	public function fetch_widget($name, $args = array()) {
		if(empty($args['call_from'])) {
			$backtrace = debug_backtrace();
			$from = $backtrace[0];
			$args['call_from'] = $from; 
		}
		ob_start();
		$this->display_widget($name, $args);
		$widget = ob_get_clean();
		return $widget;
	}

	public function add_snippet_root_directory($directory) {
		$this->_snippets_root_directories[] = $directory;
	}	
	/*
	 * This method is really check if someone can edit a file by it's path...
	 * @param string $file_path A real fullpath to some file...
	 * @return boolean
	 * **/
	public function can_edit_file($file_path) {
		$available_directories = $this->get_snippets_root_directories();
		$directories = array();
		$parent = dirname($file_path);
		while(($parent != '/')) {
			$directories[] = $parent;
			$parent = dirname($parent);
		}	
		foreach($available_directories as $directory) {
			foreach($directories as $_directory) {
				if($directory == $_directory) return true;
			}	
		}		
		return false;
	}

	/**
	 * This method cleans the path variables with or without encode|decode functions
	 * @param string $filepath A filepath variable, decoded or not...
	 * @param boolean $do_decode If you provide an encoded filepath this method can automaticaly decodes it. Optional. Default: true
	 * @return string $filepath Return a fully cleared string variable...
	 * */
	public function clean_user_filepath($filepath, $do_decode = true) {
		if($do_decode == true) {
			$filepath = $this->decode_filepath($filepath); 
		}
		$replaces = array(
			'../' => '',
			"\x0" => ''
		);
		$filepath = str_replace(array_keys($replaces), array_values($replaces), $filepath);
		return $filepath;
	}

	public function encode_filepath($filepath) {
		return base64_encode($filepath);
	}
	public function decode_filepath($filepath) {
		return base64_decode($filepath);
	}

	public function get_relative_filepath($fullpath) {

	}
	public function get_snippets_root_directories() {
		return $this->_snippets_root_directories;
	}	
	/*
	 * This method initializes a system view object, that helps to display a lot of internal HTML markup
	 * @return \PureLib\View
	 * **/
	public function view() {
		$view = \PureLib\Registry::get('view');
		if(!is_object($view)) {	
			$view = new \PureLib\View( __DIR__ . '/templates' );
			
			$view->register_helper('Path', new \Pure\Helper\View\Path());
			$view->register_helper('Widget', new \Pure\Helper\View\Widget());

			\PureLib\Registry::set('view', $view);
			if($this->settings()->get('suppress_view_warnings', true)) {
				$view->suppress_warnings(true);	
			}
		}
		return $view;
	}
	/*
	 * This method initializes a global \Pure\InlineWrapper object to work with content snippers, widgets and other functions...
	 * @return \Pure\InlineWrapper
	 * **/
	public function worker() {
		$worker = \PureLib\Registry::get('InlineWrapper');
		if(!is_object($worker)) {
			$worker = new \Pure\InlineWrapper();
			\PureLib\Registry::set('InlineWrapper', $worker);
		}	
		return $worker;
	}
	/*
	 * This basic method just helps to display error messages just in HTML content of the website.
	 * @param string $message
	 * @param array $args TODO: An array of advanced configuration.
	 * **/
	public function error_box($message, $args = array()) {
		$args = new \PureLib\Config\RawArray($args, array(
			'echo' => true
		));
		$content = $this->view()->fetch('raw_error.php', compact('message'));

		if($args->get('echo', true)) echo $content;
		else return $content;
	}

	public function is_post_request() {
		return $this->request()->is_post_request();	
	}
	/**
	 * This method returns and initializes a WAMT modules.
	 * @return Instances for a module.
	 * */
	public function module($name) {
		if(!is_string($name) || empty($name)) {
			throw new \Pure\Exception("You must provide a valid module name. Check your code please.");
		}

		if(empty($this->_loaded_modules[$name])) {
			$classname = "\Pure\Module\\".$name."\Module";
			if(!class_exists($classname, true)) {
				throw new \Pure\Exception("You try to load unknown Module named ``{$name}``. Search for class ``{$classname}`` failed!");
			}
			$settings = $this->settings();	
			$module_settings = $settings->get($name, array());	
			$module = new $classname($this, $module_settings);	
			$this->_loaded_modules[$name] = $module;
		}	
		return $this->_loaded_modules[$name];
	}
	public function settings() {
		return $this->config();
	}
	/*
	 * This is internal method added for support a basic configurations.
	 * TODO: We need to supprot a config specify path
	 * **/
	public function config($custom_path = null) {
		if(is_object($this->_config)) return $this->_config;

		$default_config = __DIR__ . '/settings.json';
		if(null == $custom_path)
			$custom_path = $default_config;
		try {
			$settings = json_decode(file_get_contents($custom_path), true); 
			
			$theme_config_file = get_template_directory() . '/pixid.json'; 
			if(file_exists($theme_config_file) && is_readable($theme_config_file)) {
				$theme_settings = json_decode(file_get_contents($theme_config_file), true);
				if(is_array($theme_settings)) {
					$settings = array_merge($settings, $theme_settings);
				}
			}
			$this->_config	= new \PureLib\Config\RawArray($settings);

		} catch(Exception $e) {
			$this->_config = new \PureLib\Config\RawArray(array(), array());
		}
		
		return $this->_config;
	}
	public function logger() {
		if(!is_object($this->_logger)) {
			$this->_logger = $this->module("Debug");
		}	
		return $this->_logger;
	}
	public function request() {
		if(!is_object($this->_request)) {
			$this->_request = new \Pure\Request();
                        \PureLib\Registry::set('request', $this->_request);
		}	
		return $this->_request;
	}
	/*
	 * This method return a collection of pathes that can be used to store a service files...
	 * **/
	public function get_preferred_data_pathes() {
		$wp_upload = wp_upload_dir();
		$pathes = array(
			__DIR__ . '/_data',
			trailingslashit($wp_upload['basedir']) . '_data'
		);
		if($this
			->config()
			->get('data_directory')) {
			array_unshift($pathes, $this->config()->get('data_directory'));	
		}
		return $pathes;
	}
	/**
	 * This method only returns a preferred and a `current` path to a current DATA directory. With a temporary files and/or other service files...
	 * */
	public function get_data_directory_path() {
		$preferred_pathes = $this->get_preferred_data_pathes();
		foreach($preferred_pathes as $path) {
			if(is_dir($path) && is_writable($path)) return $path;	
		}	
		foreach($preferred_pathes as $path) {
			//try to create a _data directory
			$base = dirname($path);
			
			if(is_dir($base) && is_writable($base)) {
				mkdir($path);
				return $path;	
			}
		}
	}
	public function data_directory() {
		if(is_object($this->_data_directory)) return $this->_data_directory();
		$this->_data_directory = new \Pure\DataDirectory($this->get_data_directory_path());
		return $this->_data_directory;
	}
	public function get_plugin_directory() {
		return __DIR__;
	}
	public function plugin_dir_url() {
		return plugin_dir_url(__FILE__) ;
	}		
	public function get_title() {
		global $aiosp;
		
		$aioseop_title = "";
		if(is_object($aiosp)) {
			$aioseop_title = $aiosp->wp_title();
		}
		$wp_title = wp_title('&raquo;', false, '|');
		if(!empty($aioseop_title)) {
			$wp_title = $aioseop_title;
		}
		if(empty($wp_title)) {
			if(is_front_page()) {
				$page_on_front = get_option('page_on_front');
				if(!empty($page_on_front)) {
					if(empty($wp_title)) {
						$page = get_page($page_on_front);
						$wp_title = $page->post_title;
					}
				}
			}
		}
		return $wp_title;
	}
	public function show_title() {
		echo $this->get_title();
	}	

	public function __call($method, $args) {
		$module = $this->module($method);
		if(($module instanceof \Pure\Module) && !($module instanceof \Pure\Module\Fake)) return $module;
		//Trigger an error here please
		throw new \Pure\Exception("Call to undefined method {$method} in class: " . get_class($this));
	}
	
	public static function instance() {
		static $instance;
		if(!is_object($instance)) {
			$instance = new self();
		}
		return $instance;
	}
}
/**
 * This is a main method which return a global initialized instance which works as a "proxy" to a lot of usefull calls in PixiD framework.
 * @return \PixiD
 * */
function PX() {
	return PixiD::instance();
}
/**
 * Back-compatible method, it will be deprecated soon.
 * */
function PM() {
	return PX(); 
}

//Just init a plugin
PX();
