<?php

namespace Pure\Module\Frontend;
class Module extends \Pure\Module {
	protected $_libraries;
	protected $_required_libraries;
	protected $_track_widgets;
	protected $_included_css;
	protected $_included_js;
	
	public function sanitize_style_name($basename, $url, $widget = null) {
		//FIXME: Need to create a really valid and readable, name for this style
		return get_class($widget) . '#' . $basename;
	}
	public function sanitize_script_name($basename, $url, $widget = null) {
		return get_class($widget) . '#' . $basename;
	}
	public function _init() {
		add_action('wp_enqueue_scripts', array($this, 'enqueue_libraries'));
		add_action('admin_enqueue_scripts', array($this, 'enqueue_internal'));
		add_action('wp_enqueue_scripts', array($this, 'enqueue_internal'));
		add_action('wp_enqueue_scripts', array($this, 'enqueue_included'));
		add_action('wp_enqueue_scripts', array($this, 'enqueue_from_widgets'));
		if($this->settings()->get("track_iframe_template", true)) {
			add_action("get_header", array($this, 'iframe_header'));
			add_action("get_footer", array($this, 'iframe_footer'));
		}
		add_filter('query_vars', array($this, 'query_vars'));
		//add_action('wp_head', array($this, 'frontend_head'));
		//add_action('admin_head', array($this, 'frontend_head'));
	}
	/*
	 * Some internal widget can use some advanced arguments which can be passed directly in REQUEST
	 * This hook helps to register those variables in Wordpress and prevent it's destruction via redirects as example.
	 * **/
	public function query_vars($vars) {
		//Some for redirection argument to prevent forms resubmission, as example: Registration form.
		$vars[] = 'success';	
		return $vars;
	}

	public function frontend_head() {
		echo "<script type=\"text/javascript\">window.PM = PM.init(); </script>";
	}

	//Next method will work only if \PureManager\Template_Processor_Module is enabled!
	public function include_js($name, $source = null, $deps = array(), $version = null) {
		$this->_included_js[] = array(
			'name' => $name,
			'source' => $source,
			'deps' => $deps,
			'version' => $version
		);
		return $this;
	}
	public function include_style($name, $source = null, $deps = array(), $version = null) {	
		$this->_included_css[] = array(
			'name' => $name,
			'source' => $source,
			'deps' => $deps,
			'version' => $version
		);	
		return $this;
	}
	public function include_css($name, $source = null, $deps = array(), $version = null) {
		return $this->include_style($name, $source, $deps, $version);
	}

	public function enqueue_included() {
		$styles = $this->_included_css;
		$scripts = $this->_included_js;
		if(is_array($styles) && !empty($styles)) {
			foreach($styles as $style) {
				wp_enqueue_style($style['name'], $style['source'], $style['deps'], $style['version']);
			}
		}	
		if(is_array($scripts) && !empty($scripts)) {
			foreach($scripts as $script) {
				wp_enqueue_script($script['name'], $script['source'], $script['deps'], $script['version']);
			}
		}
	}

	public function enqueue_from_widgets() {
		if(empty($this->_track_widgets)) return $this;
		foreach($this->_track_widgets as $widget) {
			
			if($this->autoload_enabled()) {
				$template = $widget->get_template();
				if(($template instanceof \Pure\Template\Template)) {	
					//Old style scripts...
					
					if($template->file_exists("css/style.css")) {	
						$style_name = $widget->get_name() . "#" . $template->get_name() . "#style";
						wp_enqueue_style($style_name, $template->get_template_directory_uri() . "/css/style.css");	
					}	
					if($template->file_exists("js/script.js")) {
						$script_name = $widget->get_name() . "#" . $template->get_name() . "#script";
						wp_enqueue_script($script_name, $template->get_template_directory_uri() . "/js/script.js");
					}
					//Load all .autoload.css files
					try {
						$directory = new \PureLib\FS\Node\Directory($template->get_template_directory() . '/css/');
						$files = $directory->get_child_nodes();
						if(!empty($files)) {
							foreach($files as $file) {
								$name = $file->get_basename();
								if(false !== strpos($name, '.autoload.css')) {
									//FIXED: We can not use get_public_uri method, due to wordpress can be installed in subdirectory 
									//$uri = $file->get_public_uri();
									$uri = str_replace(ABSPATH, trailingslashit(home_url()), $file->get_fullpath());
									$style_name = $this->sanitize_style_name($file->get_basename(), $uri, $widget);
									wp_enqueue_style($style_name, $uri);	
								}	
							}
						}
					} catch(\Exception $e) {
						
					}
					//Load all .autoload.js files
					try {
						$directory = new \PureLib\FS\Node\Directory($template->get_template_directory() . '/js/');
						$files = $directory->get_child_nodes();
						if(!empty($files)) {
							foreach($files as $file) {
								$name = $file->get_basename();
								if(false !== strpos($name, '.autoload.js')) {
									//$uri = $file->get_public_uri();
									//FIXED: We can not use get_public_uri method, due to wordpress can be installed in subdirectory 
									//$uri = $file->get_public_uri();
									$uri = str_replace(ABSPATH, trailingslashit(home_url()), $file->get_fullpath());
									$style_name = $this->sanitize_script_name($file->get_basename(), $uri, $widget);
									wp_enqueue_script($style_name, $uri);	
								}	
							}
						}
					} catch(\Exception $e) {
						
					}
				}
			}
		}

	}

	public function autoload_enabled() {
		return $this->settings()->get('autoload_frontend_files', true);
	}
	public function track_widget_includes(\Pure\Widget $widget) {
		$this->_track_widgets[] = $widget;
	}

	public function iframe_header() {

		//echo "Header called!";
	}
	public function iframe_footer() {
		//echo "Footer called!";
	}



	protected function _load_libraries_data() {
		if(!empty($this->_libraries)) return $this;

		$data = file_get_contents(__DIR__ . '/libraries.json');
		$replaces = array(
			'#WAMT_ROOT#' => PM()->plugin_dir_url() . ''
		);	

		$data = str_replace(array_keys($replaces), array_values($replaces), $data);
		$data = json_decode($data, true);
		$this->_libraries = $data;
	}
	public function require_lib($library_name) {
		$this->_required_libraries[] = $library_name;	
	}
	public function enqueue_libraries() {
		$this->_load_libraries_data();
		//FIXME: This method need a fixes!
		if(empty($this->_required_libraries)) return $this;
		foreach($this->_required_libraries as $library_name) {	
			$library = $this->_libraries[$library_name];
			$js_files = $library['js_files'];
			$css_files = $library['css_files'];
			$deps = $library['deps'];
			if(!empty($deps)) {
				foreach($deps as $dep) {
					wp_enqueue_script($dep);
				}
			}
			if(!empty($js_files)) {
				foreach($js_files as $file) {
					wp_enqueue_script($library_name, $file);
				}
			}
			if(!empty($css_files)) {
				foreach($css_files as $file) {
					wp_enqueue_style($library_name, $file);
				}
			}
		}
	}
	public function enqueue_internal() {
		wp_enqueue_script('pm-utils', PM()->plugin_dir_url() . '/js/utils.js');
		//wp_enqueue_script('pm', PM()->plugin_dir_url() . '/js/pm.js');
	}
	public function redirect($location) {
		return $this->js_redirect($location);
	} 
	public function js_redirect($location) {
		//FIXME Track if output started?
		echo "<script type=\"text/javascript\">window.location = '{$location}'; </script>";
		return true;
	}
}
