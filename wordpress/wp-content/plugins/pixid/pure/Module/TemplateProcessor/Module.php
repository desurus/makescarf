<?php

namespace Pure\Module\TemplateProcessor;
class Module extends \Pure\Module {
	protected $_header_name = "";	

	protected function _init() {
		add_action('wp_head', array($this, 'fake_wp_head'), 0);	
		add_action('wp_footer', array($this, 'fake_wp_footer'), 0);
		add_filter('template_include', array($this, 'template_include'), 0, 1);
		register_shutdown_function(array($this, 'shutdown'));
		add_action('shutdown', array($this, 'end_ob_cache'), 0);
	}
	public function shutdown() {
		$c = ob_get_contents();	
	}
	
	public function template_include($template) {
		ob_start();
		return $template;
	} 
		

	public function end_ob_cache() {
		$content = ob_get_clean();
		$content = $this->_get_late_header_code($content);
		$content = $this->_get_late_footer_code($content);
		echo $content;	
	}

	public function fake_wp_head() {
		global $wp_filter;
		$wp_filter['_wp_head'] = $wp_filter['wp_head'];
		
		array_shift($wp_filter['_wp_head']);
		$wp_filter["wp_head"] = array();
		echo "<!--#PM_WP_HEAD#-->";
	}

	public function fake_wp_footer() {
		global $wp_filter;
		$wp_filter['_wp_footer'] = $wp_filter['wp_footer'];	
		array_shift($wp_filter['_wp_footer']);
		$wp_filter["wp_footer"] = array();
		echo "<!--#PM_WP_FOOTER#-->";
	}

	public function start_buffering() {
		if (version_compare(PHP_VERSION, '5.4.0', '>=')) {
			ob_start(null, 0, PHP_OUTPUT_HANDLER_STDFLAGS ^ PHP_OUTPUT_HANDLER_REMOVABLE ^ PHP_OUTPUT_HANDLER_CLEANABLE);	
		} else {
			ob_start(null, 0, false);
		}
	}	

	protected function _get_late_header_code($header_code) {
		$real_header = "";
		ob_start();	
		do_action('_wp_head');	
		$real_header = ob_get_clean();		
		if(defined('WP_DEBUG') && WP_DEBUG)	$real_header = "<!-- The header was parsed with PixiD TemplateProcessor Module! -->\n" . $real_header;
		$header_code = str_replace("<!--#PM_WP_HEAD#-->", $real_header, $header_code);
		return $header_code;
	}

	protected function _get_late_footer_code($footer_code) {
		$real_footer = "";
		//Seems this is the easiest way to do it
		ob_start();	
		do_action('_wp_footer');	
		$real_footer = ob_get_clean();	
		$footer_code = str_replace("<!--#PM_WP_FOOTER#-->", $real_footer, $footer_code);
		return $footer_code;
	}

	public function early_header($name) {	
		$this->_header_name = $name;
		ob_start();	
	}
		
	public function early_footer($name) {
		$content = ob_get_clean();
		ob_start();
		get_header($this->_header_name);
		$header = ob_get_clean();	
		ob_start();
		get_footer($name);	
		$footer = ob_get_clean();
		//Working with header late data	
		$header = $this->_get_late_header_code($header);
		$footer = $this->_get_late_footer_code($footer);
		echo $header . $content . $footer;
	}

	
}
