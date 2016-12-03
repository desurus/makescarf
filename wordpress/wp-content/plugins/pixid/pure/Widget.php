<?php
/**
 * This class required to declare all Widget which can use an WAMT environment and API.
 * Please extend this class in your widget freely.
 * Please, do not override the default constructor fot this class if it's absolutelly unneccessary. Use ::_init() protected method instead, if you just want to do some stuff on widget initialization.
 * Now all widgets supports own simple localization functions tr()
 * @author Shell
 * @version 0.0.23
 * 0.0.22
 * 	In this version the widget UID generation was fixed. @see ::get_uid method for more details about this.
 * 0.0.21
 * 	Some methods are deprecated in latest version due to a class goes very large. We need to have a stable "API". Some of methods marks as deprecated and will trigger an "deprecated" notices in near feature.
 * */
namespace Pure;

abstract class Widget {
	protected $_args;
	
	protected $_include_js;
	protected $_include_css;
	protected $_require_libs;
	protected $_finder;
	
	public function __construct($args = array()) {
		$this->_include_js = $this->_include_css = array();	
		$this->set_args($args);
		
		PX()
			->module("Frontend")
			->track_widget_includes($this);

		$this->_init();
		$this->_store_args();	
	}
	/**
	 * This method stores a widget call args in a Session!
	 * Seems this is not a temporary solution, this is a best solution at this moment.
	 * */
	protected function _store_args() {
		$uid = $this->get_uid();
		$call = new \Pure\Widget\Call($this->find_callcode());
		$call->set_widget_call_args($this->args()->to_array());	
		\Pure\Helper\Session::store("widget_call_{$uid}", maybe_serialize($call));	
	}
	protected function _load_args_from_session() {
		$uid = $this->get_uid();
		$args = maybe_unserialize(\Pure\Helper\Session::get("widget_call_{$uid}"));
		return $args;
	}
	/**
	 * Method generated a Widget unique ID which can be stored somewhere to uniq identify this widget call.
	 * At this moment the best way to do it - by compare all widget call arguments. This way is easy and transparent, and more widgets with same args call MAY really shows the same results as the "origin".
	 * This way may been restructorized in future. But at this moment this is very well solution in my opinion.
	 * TODO: We do not appear about performance and compatibility issues here at this moment.
	 * @return string $uid Return the md5 string from all widget args builded HTTP query string.
	 * */
	public function get_uid() {	
		$args = $this->get_args();
		unset($args['call_from']);	
		$s = http_build_query($args->to_array());	
		return md5($s);
	}
	/*
	 * Please override this method instead of constructor, if you want to do some stuff on widget initialization.
	 * **/
	protected function _init() {
	
	}
	
	public function get_name() {
		return get_class($this);
	}	

	public function set_args($args = array()) {
		$this->_args = $args;
	}
	public function get_args() {
		if(!is_object($this->_args)) $this->_args = new \PureLib\Config\RawArray($this->_args);
		return $this->_args;
	}
	public function args() {
		return $this->get_args();
	} 
	
	public function find_template($template_name = null) {
		if($template_name === null) {
			$template_name = $this->args()->get('template', 'default');
		}
		try {
			$finder = new \Pure\Template\Finder(get_class($this), $template_name); 
			$template = $finder->get_template();
		} catch(\Pure\Template\Exception $e) {
			$finder = new \Pure\Template\Finder(get_class($this), 'default');
			$template = $finder->get_template();
		}
		if(false === $template && $this->is_extended_widget()) {
			$parent_class = get_parent_class($this);
			try {
				$finder = new \Pure\Template\Finder($parent_class, $template_name);
				$template = $finder->get_template();
			} catch(\Pure\Template\Exception $e) {
				$finder = new \Pure\Template\Finder($parent_class, 'default');
				$template = $finder->get_template();
			}
		}	
		return $template;
	}
	public function display_template($assign = array(), $_ = null) {
		$custom_template = false;
		if(is_string($assign)) {
			$custom_template = $assign;
			$assign = $_;
		}
		if(!($assign instanceof \Pure\Template\Data)) {
			$assign = new \Pure\Template\Data($assign);
		}	
		$template = $this->find_template($this->args()->get('template', 'default'));	
		if(!is_object($template)) {
			$class_name = get_class($this);
			$template_name = $this->args()->get('template', 'default');
			return $this->error_box("Can not find template named '{$template_name}' for widget '{$class_name}'.");
		}

		
		$assign->add('params', $this->args());
		if(!$assign->get('data')) {
			//Seems some widget provide own "data" variable?
			foreach($assign->get('data', array()) as $key => $value) {
				$assign->add($key, $value);
			}
			$assign->remove('data');
		}
		extract($assign->get_array(), EXTR_SKIP);
		$data = $assign;
		if(!$custom_template) {
			$file = $template->get_main_template_file();	
		} else {
			$file = $template->get_custom_path($custom_template);	
		}
		if(!file_exists($file)) {
			$error = sprintf(__('Template ``%s`` does not have a template file with path ``%s``'), $template->get_name(), $file);
			return $this->error_box($error);
		}
		include $file;
		return false;
	}
	public function fetch_template($arg1, $arg2 = array()) {
		ob_start();
		$this->display_template($arg1, $arg2);
		$content = ob_get_clean();
		return $content;
	}

	public function get_template_name() {
		return $this->args()->get('template', 'default');	
	}
	public function get_internal_templates_directory() {
		$class_name = get_class($this);
		$autoloader = \PureLib\Registry::get('autoloader');
		$file = $autoloader->get_filename_by_class($class_name);
		$widget_root = dirname($file);
		return $widget_root . "/templates";
	}
	/**
	 * This method just checks if the widget can have a templates directory.
	 * */
	public function has_templates() {
		return is_dir($this->get_internal_templates_directory());
	}

	public function maybe_display_error($error) {
		echo "<p class=\"pm-error pm-message\">{$error}</p>\n";
		return;
	}
	/*
	 * This method just returns an array with very simple buttons, like array('icon' => '', title => '', 'link' => '') 
	 * */
	public function get_simple_edit_buttons() {
		return array();
	}
	/*
	 * This method may help to show frequently used buttons in lot of widgets....
	 * **/
	public function get_internal_buttons() {
		$buttons = array();
		//Check if we need to show an template create button
		$template = $this->find_template();
		if(!is_object($template)) {
			return $buttons;
		}	
		$widget_name = get_class($this);

		$buttons[] = new \Pure\Editor\Button(array(
			'icon' => 'admin-page',
			'title' => 'Copy this widget template',
			'link' => \Pure\Helper\Url::ajax('pm_copy_template', array(
				'widget' => \Pure\Helper\Data::encode($widget_name), 
				'template' => \Pure\Helper\Data::encode($template->get_name())
				)
			),
                        'dialog_width' => 600,
                        'dialog_height' => 300,
		       	'modal' => true	
			)
		);
		if($this->has_editable_options()) {
			$call_data = $this->find_callcode();
			if(!empty($call_data)) {
				$buttons[] = new \Pure\Editor\Button(
					array(
						'icon' => 'admin-settings',
						'title' => __('Configure this Widget'),
						'link' => \Pure\Helper\Url::ajax('pm_configure_widget', array(
							'widget' => \Pure\Helper\Data::encode($widget_name),
							'call' => \Pure\Helper\Data::encode(serialize($call_data))		
						)),
						'dialog_width' => 600,
						'dialog_height' => 800,
						'modal' => true
					)
				);			
			}
		}
		return $buttons;
	}

	public function error_box($message, $args = array()) {
		return PM()->error_box($message, $args);
	}
	
	abstract public function widget();

	protected function _finder() {
		if(!is_object($this->_finder)) {
			$this->_finder = new \Pure\Widget\Finder(get_class($this));
		}
		return $this->_finder;
	}
	public function finder() {
		return $this->_finder();
	}

	public function get_template() {
		$finder = new \Pure\Template\Finder(get_class($this), $this->args()->get('template', 'default'));
		$template = $finder->get_template();
		return $template;	
	}
		
	public function get_widget_directory_uri() {
		return $this->_finder()->get_directory_uri();			
	}
	public function get_widget_directory() {
		return $this->_finder()->get_directory();
	}

	public function get_template_directory_uri() {
		return $this
			->get_template()
			->get_template_directory_uri();
	}
	//Next method will work only if \PureManager\Template_Processor_Module is enabled!
	public function include_js($name, $source = null, $deps = array(), $version = null) {
		PM()
			->Frontend()
			->include_js($name, $source, $deps, $version);
		return $this;
	}
	public function include_style($name, $source = null, $deps = array(), $version = null) {	
		PM()
			->Frontend()
			->include_css($name, $source, $deps, $version);
		return $this;
	}

	public function include_css($name, $source = null, $deps = array(), $version = null) {
		return $this->include_style($name, $source, $deps, $version);
	}
	public function require_lib($library) {
		//$this->_require_libs[] = $library;
		PM()->Frontend()->require_lib($library);
		return $this;
	}	
	
	public function find_callcode() {
		$backtrace = debug_backtrace();
		$call_from = array();
		foreach($backtrace as $trace) {
			if(!isset($trace['class']) || $trace['class'] != 'Pure_Manager_Plugin') continue;
			if(empty($trace['args']) || !is_string($trace['args'][0])) continue;
			$string = $trace['args'][0];	
			if(substr($string, 0, 1) == '\\') {
				$string = substr_replace($string, '', 0, 1);
			}	
			if($string == get_class($this)) {
				$call_from = array(
					'file' => $trace['file'],
					'line' => $trace['line'],
					'args' => $trace['args']
				);	
			}
		}	
		if(empty($call_from)) return;
		return $call_from;
	}

	public function can_change_template() {
		return $this->has_templates();
	}
	public function find_all_templates() {
		
	}
	public function get_internal_settings_objects() {
		
	}
	public function get_widget_settings_container() {
		
	}

	public function ajax_url($action = '', $params = array()) {
		$params = array_merge(array('wid' => $this->get_uid()), $params);
		return $this->Url()->widget_ajax($this->get_name(), $action, $params);	
	}
	public function get_included_styles() {
		return $this->_include_css;
	}
	public function get_included_scripts() {
		return $this->_include_js;
	}
	/**
	 * As default, this method just checks if some Widget has a template enabled...
	 * This is not a really right way I tkink, but at this moment this is a best way. 
	 * */
	public function has_editable_options() {
		$template = $this->find_template();
		return is_object($template);	
	}	
	/* *
	 * NO ANY QUICK_USAGE methods!
	 * We can not store a lot of methods in code, due to classes goes very large... This method is deprecated now!
	 * Please use ::get_request()->is_request_post() now.
	 * **/	
	public function is_post_request() {	
		\Pure\Debug::deprecated_method(__METHOD__, __CLASS__ . '::get_request()->is_request_post()', '0.0.21');	
		return \Pure\Helper\Request::is_post_request();
	}
	/**
	 * This method really checks if this Widget class extends another REAL widget.
	 * */
	public function is_extended_widget() {
		$skip_parents = array(
			'Pure\Widget',
			'Pure\Widget\Internal'	
		);
		$parent = get_parent_class($this);
		if(empty($parent)) return false;
		if(false === array_search($parent, $skip_parents)) return true;
		return false;	
	}
	/**
	 * This method returns an initialized instance of current request data container. Which can be used to retrieve a request variables.
	 * @from version 0.0.21
	 * @return \Pure\Request */
	public function get_request() {
		return \Pure\Request::instance();
	}
	/**
	 * This method is deprecated from version 0.0.21
	 * Please, use ::get_request instead.
	 * */
	public function request() {
		\Pure\Debug::deprecated_method(__METHOD__, __CLASS__ . '::get_request', '0.0.21');
		return $this->get_request(); 
	}
	/**
	 * TEMPORARY:
	 * Helper call methods...
	 * */
	public function Url() {
		return \Pure\Helper\Url::instance();
	}
	/**
	 * This method can be used to translate widget strings.
	 * Because our widgets are very small functional peaces: storing translations in traditional gettext way is little bit overheader, so we have own simple translation storage way.
	 * All widgets have internal language messages in english located in basic $WIDGET_DIR$/languages/ directory. And any template can have different translation strings it-self.
	 * At this version we support only translations in templates. Other functinal will be writen little later.
	 * @param string $string The string which need to be translated. If no translation found: this string will be returned "as is".
	 * @return string Translated string if any founded.
	 * */
	public function tr($string) {
		$current_language = PX()
					->Multilingual()
					->get_current_language_code();
		
		$template_directory = $this
					->get_template()	
					->get_template_directory();
		//We store original strings in english locale
		if($current_language == 'en-US')	 return $string;
		//Language directory does not exists in templates dir, so, we can not translate any string in this widget
		if(!is_dir($template_directory . "/languages")) return $string;	
		//Current language is not available in this widget template...
		if(!file_exists($template_directory . "/languages/{$current_language}.php")) return $string;
		//We use pretty static cache, our translations will be loaded once, and stored in memory.
		
		$cache_key = \PureLib\Cache\StaticCache::sanitize_for_key(array(
			'widget' => get_called_class($this),
			'locale' => $current_language
		));
		
		$translation_file = $template_directory . "/languages/{$current_language}.php";
		$translation = \PureLib\Cache\StaticCache::get('Widget::tr::' . $cache_key, function() use($translation_file) {
			include $translation_file;
			return $translation;
		});
		if(!isset($translation[$string])) return $string;
		return $translation[$string];
	}
	/*
	 * This method tries to get a widget call in current session!
	 * @param string $wid Widget id
	 * @return mixed
	 * **/
	public static function find_call_in_session($wid) {
		$data = \Pure\Helper\Session::get("widget_call_{$wid}", false);
		if(false != $data) {
			$data = maybe_unserialize($data);
		}	
		return $data;
	}
}
