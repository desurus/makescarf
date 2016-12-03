<?php

namespace MakeScarf\Widget\Constructor;

class Widget extends \Pure\Widget\Internal {
	protected $_makescarf_options;
	protected function _init() {
		$this->_makescarf_options = get_option('makescarf_settings', array(
			'default_price' => 68
		));
		if(!$this->args()->get('default_price')) {
			$this->args()->set('default_price', $this->_makescarf_options['default_price']);
		}
	}
	public function get_real_color_value($color_name) {
		$colors = $this->get_colors();
		$color = false;
		foreach($colors as $_color) {
			if($_color['color'] == $color_name)
				return $_color['colorValue'];
		}
		return false;
	}

	public function get_color_nicename($color_value) {
		$colors = $this->get_colors();
		foreach($colors as $color) {
			if($color['colorValue'] == $color_value) return $color['title'];
		}
		return 'Unknown';
	}
	public function get_color_name($color_value) {
		$colors = $this->get_colors();
		foreach($colors as $color) {
			if($color['colorValue'] == $color_value) return $color['color'];
		}
		return '';
	}
	public function get_colors() {
		return array(
			array('color' => 'biege', 'title' => 'Biege', 'colorValue' => '#e5e0d6'),
			array('color' => 'white', 'title' => 'White', 'colorValue' => '#fff')
		);
	}
	public function mce_include_plugins() {	
		$plugins = array(
			
		);
	}

	public function mce_external_plugins() {
		$plugins = array(
			'Constructor' => $this->get_widget_directory_uri() . '/js/tinymce/plugins/constructor/plugin.js',
			'placeholder' => $this->get_widget_directory_uri() . '/js/tinymce/plugins/placeholder/plugin.js'
		);
		return $plugins;	
	}
	
	public function mce_config( $settings ) {
		$_settings = \NMakeScarf_Theme::instance()->get_settings();
		$settings['constructor_background_colors'] = json_encode(
			
				$this->get_colors()	
			
		);
		$settings['fontsize_formats'] = '120pt 240pt 320pt';

		$settings['font_formats'] = 'Gothic=gothic;My Underwood=MyUnderwood;Zah=Zah';
		$settings['content_css'] = get_template_directory_uri() . '/constructor/fonts.css';
		$settings['init_instance_callback'] = "function(ed) { ed.plugins.Constructor.initValues(); }";
		$settings['statusbar'] = false;
		$settings['entity_encoding'] = "raw";
		$settings['removeformat'] = "false";
		$settings['zoomFullScreen'] = $_settings['fullscreen_zoom'];
		$settings['zoomNormal'] = $_settings['normal_zoom'];
		$settings['indent'] = $_settings['constructor_indent'];
		return $settings;
	}

	public function get_session_constructor() {
		
	}
	public function widget() {
		$select_posts = array();
		if(!empty($_REQUEST['select_posts']) && is_array($_REQUEST['select_posts'])) {
			foreach($_REQUEST['select_posts'] as $post_ID) {
				$post_ID = intval($post_ID);
				if($post_ID <= 0) continue;
				$select_posts[] = $post_ID;
			}
		}
		$text = "";
		if(!empty($select_posts)) {
			foreach($select_posts as $post_ID) {
				$post = get_post($post_ID);
				//FIXME: Need to check a post access...
				if($post->post_status != 'publish' || $post->post_type != 'post') {
					continue;
				}	
				$text .= "{$post->post_content}";
			} 
		}
		$constructor = array();
		$preview_url = "";
		if($this->args()->get('scarf_id') && PM()->User()->is_logged_in())	 {
			$user = PM()->User()->get_current_user();

			$product = PM()->Woo()
					->get_product_by_id($this->args()->get('scarf_id'));
			if($product->post->post_author == $user->ID) {
				$constructor = array(
					'layout' => strtolower($product->get_attribute('layout')),
					'color' => $this->get_color_name($product->get_attribute('color')),
					'fontsize' => $product->get_attribute('fontsize'),
					'font' => $product->get_attribute('font'),
					'style' => $product->get_attribute('style'),
					'product_id' => $product->post->ID
				);
				$text = $product->post->post_content;
			}
			$preview_url = add_query_arg('full_preview', 'true', get_permalink($this->args()->get('scarf_id')));
		}
		if(!empty($_COOKIE['makescarf_constructor'])) {
			
			$constructor = unserialize(base64_decode($_COOKIE['makescarf_constructor']));
			$text = @$constructor['body'];	
			$text = stripslashes_deep($text);
			//$text = '';
			//\Pure\Debug::dump($text);
			//Here we have a some interesting bug with font-size...
				
		}
		if(empty($constructor['layout'])) {
			$constructor['layout'] = 'horizontal';
		}
		if(empty($constructor['color'])) {
			$constructor['color'] = 'biege';
		}
		if(empty($constructor['fontsize'])) {
			$constructor['fontsize'] = '240pt';
		}
		if(empty($constructor['font'])) {
			$constructor['font'] = 'MyUnderwood';
		}
		if(empty($constructor['style'])) {
			$constructor['style'] = 'infinity';
		}
		if(!empty($text)) {
			$constructor['skip_default_styles'] = true;	
		}
		if(empty($text)) {
			$text = "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.";
		}
		$editor = array(
			'text' => $text
		);
		
		add_action('tiny_mce_before_init', array($this, 'mce_config'));
		add_action('mce_external_plugins', array($this, 'mce_external_plugins'));
		//$constructor = $this->get_session_constructor();	
		
		$this->display_template(array(
			'editor' => $editor,
			'constructor' => $constructor,
			'scarf_id' => $this->args()->get('scarf_id', 0),
			'preview_url' => $preview_url
		));
	}
}
