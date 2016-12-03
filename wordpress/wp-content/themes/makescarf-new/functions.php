<?php
//ini_set('display_errors', 'On');
//error_reporting(E_ALL);
class NMakeScarf_Theme extends \PureLib\Wordpress\Theme {

	protected function _hooks() {
		add_action('wamt_after_init', array( $this, 'wamt_after_init' ));
		add_action('woocommerce_cart_item_thumbnail', array($this, 'cart_item_thumbnail'), 10, 3);
		//add_action('admin_menu', array($this, 'admin_menu'));
		add_filter('fep_preto_filter', array($this, 'fep_preto_filter'), 1, 1);
		add_action('template_redirect', array($this, 'template_redirect'));
		add_action('template_redirect', array($this, 'maybe_account_page'));
		add_action('parse_query', array($this, 'parse_query'));
		add_action( 'add_meta_boxes', array($this, 'register_scarf_metabox') );
		add_action( 'wp_print_scripts', array($this, 'remove_password_strenght'), 100 );
	}
	public function remove_password_strenght() {
		if ( wp_script_is( 'wc-password-strength-meter', 'enqueued' ) ) {
			wp_dequeue_script( 'wc-password-strength-meter' );
		}
	}
	public function maybe_account_page() {
		$uri = $_SERVER['REQUEST_URI'];
		if(false !== strpos($uri, '/account') && $uri != '/account/login' && false === strpos($uri, '/account/lost-password')) {
			if(!is_user_logged_in()) {
				Header("Location: /account/login/");
				exit();
			}
		}	
	}
	public function register_scarf_metabox() {
		add_meta_box( 'meta-box-scarf', 'Scarf PDF File', array($this, 'scarf_metabox'), 'product' );
	}
	public function scarf_metabox() {
		global $post;
		PM()->display_widget("\MakeScarf\Widget\ScarfFile\Widget", array(
			'post_id' => $post->ID
		));	
	}
	public function parse_query($wp_query) {
		if($wp_query->is_main_query()) {
			$settings = $this->get_settings();
			$allowed_ips = $settings['printer_allowed_ips'];
			//var_dump(get_theme_mod('makescarf_allowed_ips'), $allowed_ips); exit();
			$ip = $_SERVER['REMOTE_ADDR'];
			if(in_array($ip, $allowed_ips))	{
				$wp_query->set('post_status', array(
					'publish', 'private'
				));		
			}		
		}
		if($wp_query->is_main_query() && is_shop()) {
			$wp_query->set_404();
		}
		return $wp_query;
	}
	public function template_redirect() {
		$o = get_queried_object();
		if(($o instanceof \WP_Post) && $o->post_type == 'product') {
			if(@$_REQUEST['full_preview'] == 'true') {
				PM()->display_widget("\MakeScarf\Widget\ScarfPreview\Widget", array("template" => "fullpage_preview", "post_id" => $o->ID));
				exit();
			}
		}
	}
	public function customize_register($wp_customizer) {
		$customizer = new \PureLib\Wordpress\Customizer($wp_customizer);
		$customizer->add_section('makescarf', 'Makescarf', 'Basic settings directly for makescarf.com project with scarf editor.');		
		$customizer->add_option('makescarf', 'text', 'default_price', array(
			'title' => 'Default scarf price',
			'default' => 68
		));
		$customizer->add_option('makescarf', 'text', 'constructor_width', array(
			'title' => 'Scarf width',
			'description' => 'Scarf width direclty in pixels or inches',
			'default' => '65in'
		));
		$customizer->add_option('makescarf', 'text', 'constructor_height', array(
			'title' => 'Scarf height',
			'description' => 'Scarf height directly in pixels or inches',
			'default' => '27in'
		));
		$customizer->add_option('makescarf', 'text', 'constructor_indent', array(
			'title' => 'Indent',
			'description' => 'Indent value from all borders',
			'default' => '225px'
		));
		$customizer->add_option('makescarf', 'text', 'normal_zoom', array(
			'title' => 'Normal zoom',
			'description' => 'CSS Zoom value in normal mode, not fullscreen',
			'default' => '0.13'
		));
		$customizer->add_option('makescarf', 'text', 'fullscreen_zoom', array(
			'title' => 'Fullscreen zoom',
			'description' => 'CSS Zoom value in fullscreen mode, or in scarf preview page',
			'default' => '0.19'
		));
		$customizer->add_option('makescarf', 'textarea', 'constructor_font_sizes', array(
			'title' => 'Font sizes',
			'description' => 'Font sizes list, can be separated with new line break.'
		));
		$customizer->add_option('makescarf', 'textarea', 'printer_allowed_ips', array(
			'title' => 'Allowed IPs',
			'description' => 'Separated with newline IPs which can be used for scarf printing utility which called directly from server console (usign a "phantomjs" script)',
			'default' => '127.0.0.1'
		));
		$customizer->add_option('makescarf', 'dropdown-pages', 'constructor_page', array(
			'title' => 'Constructor page',
			'description' => 'Plaese, select a page where Constructor widget located, this required to generate URLs to this page in template files.',
			'default' => ''
		));
	}
	public function fep_preto_filter($msg_to) {
		if(!current_user_can('administrator'))
			return 'Ayanami';
		return $msg_to;	
	}

	public function admin_menu() {
		add_options_page('MakeScarf Settings', 'MakeScarf', 'administrator', 'makescarf-settings', array($this, 'settings_page'));
	}

	public function settings_page() {
		PM()->display_widget("\MakeScarf\Widget\Settings\Widget", array());	
	}

	public function cart_item_thumbnail($image, $cart_item, $cart_item_key) {
		$scarf_id = $cart_item["data"]->post->ID;	
		$contents = PM()->fetch_widget("MakeScarf\Widget\ScarfPreview\Widget", array("post_id" => $scarf_id, "template" => "default"));	
		return $contents;
	}

	public function wamt_after_init($pm) {
		$pm->register_widgets_ns('MakeScarf', array( __DIR__ . '/PM' ));
	}

	public function after_setup_theme() {
		register_nav_menu('main_menu', 'Main menu');
		register_nav_menu('account_menu', 'Account menu');
		add_editor_style("css/style.css");
		add_theme_support('post-thumbnails');
	}

	public function enqueue_scripts() {
		wp_enqueue_style('bootstrap', get_template_directory_uri() . '/css/bootstrap.css');
		wp_enqueue_style('bootstrap-theme', get_template_directory_uri() . '/css/bootstrap-theme.css');
		wp_enqueue_style('style', get_template_directory_uri() . '/css/style.css');
		wp_enqueue_style('style-main', get_template_directory_uri() . '/style.css');
		wp_enqueue_style('media', get_template_directory_uri() . '/css/media.css');
		//wp_enqueue_style('font-awesome', get_template_directory_uri() . '/css/font-awesome.min.css');
		wp_enqueue_style('font-awesome', 'https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css');
		wp_enqueue_style('owl-carousel', get_template_directory_uri() . '/css/owl.carousel.css');
		wp_enqueue_style('owl-transitions', get_template_directory_uri() . '/css/owl.transitions.css');
		wp_enqueue_style('owl-theme', get_template_directory_uri() . '/css/owl.theme.css');
		wp_enqueue_script('bootstrap', get_template_directory_uri() . '/js/bootstrap.js', array( 'jquery' ), null, true);	
		wp_enqueue_script('owl-carousel', get_template_directory_uri() . '/js/owl.carousel.js', array( 'jquery' ), null, true);
		wp_enqueue_script('makescarf-theme', get_template_directory_uri() . '/js/script.js', array( 'jquery' ), null, true);	
		//wp_enqueue_style('constructor', get_template_directory_uri() . '/css/constructor.css');
		//wp_enqueue_script('my-tinymce', get_template_directory_uri() . '/js/tinymce.min.js');
		//wp_enqueue_style('font-awesome', '//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css')

		//wp_enqueue_script('my-tinymce', '//cdn.tinymce.com/4/tinymce.min.js');	
		//wp_enqueue_script('constructor', get_template_directory_uri() . '/js/constructor.js');
		if(!current_user_can('administrator')) 
			wp_enqueue_script('messaging', get_template_directory_uri() . '/js/messaging.js');
		wp_enqueue_style('makescarf-main', get_template_directory_uri() . '/css/main.css');
		wp_enqueue_script('jquery-form-styler', get_template_directory_uri() . '/js/jquery.formstyler.min.js', array('jquery'));
		wp_enqueue_script('makescarf-main', get_template_directory_uri() . '/js/main.js', array('jquery'));
		wp_register_script('jquery-colorpicker', get_template_directory_uri() . '/js/colorpicker.js', array('jquery'));
	}
	public function editor_buttons($first, $second) {
		var_dump($first, $second); die();
	}
	public function get_settings() {
		$settings = array(
			'constructor_width' => get_theme_mod('constructor_width', '65in'),
			'constructor_height' => get_theme_mod('constructor_height', '27in'),
			'constructor_indent' => get_theme_mod('constructor_indent', '225px'),
			'normal_zoom' => get_theme_mod('normal_zoom', '0.13'),
			'fullscreen_zoom' => get_theme_mod('fullscreen_zoom', '0.20'),
			'constructor_page' => get_theme_mod('constructor_page'),
			'default_price' => get_theme_mod('default_price', 68)
		);
		$allowed_ips = get_theme_mod('printer_allowed_ips', false);
		if(!empty($allowed_ips)) {
			$_ = explode("\n", $allowed_ips);
			foreach($_ as $key => $v) {
				$v = trim($v);
				if(empty($v)) unset($_[$key]);
			}
			$allowed_ips = $_;
		} else {
			$allowed_ips = array('127.0.0.1');
		}
		$settings['printer_allowed_ips'] = $allowed_ips;
		return $settings;
	}
	public function get_default_price() {
		$settings = $this->get_settings();
		return $settings['default_price'];
	}
	public function get_constructor_page_url() {
		$settings = $this->get_settings();
		return get_permalink($settings['constructor_page']);
	}

	public static function instance() {
		static $instance;
		if(!is_object($instance)) $instance = new self();
		return $instance;
	}
}
class MakeScarf extends NMakeScarf_Theme {

}
MakeScarf::instance();
