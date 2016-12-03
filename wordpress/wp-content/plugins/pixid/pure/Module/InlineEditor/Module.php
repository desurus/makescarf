<?php
/*
 * Этот модуль позволяет легко редактировать настройки виджетов
 * Редактировать содержимое подключаемых файлов и многое другое.
 * TODO: More detailed and valid module description.
 * TODO: Maybe some of extended libraries need to be moved directly to this module directory.
 *
 * @author Shell
 * @version 0.0.3
 * **/
namespace Pure\Module\InlineEditor;
class Module extends \Pure\Module {	
	protected $_content_css;

	protected function _init() {
		

		add_action('wp_ajax_pm_snippet_edit', array( $this, 'snippet_edit_action' ));
		add_action('wp_ajax_pm_convert_code', array($this, 'ajax_convert_code'));
		add_action('wp_ajax_pm_save_code', array($this, 'ajax_save_code'));
		add_action('wp_ajax_pm_copy_template', array($this, 'ajax_copy_template'));
		add_action('wp_ajax_pm_configure_widget', array($this, 'ajax_configure_widget'));
			
		add_action('admin_enqueue_scripts', array($this, 'maybe_enqueue_editor_files'));
		
		add_action('admin_head', array($this, 'admin_head_code_snippet'));
		add_filter( 'tiny_mce_before_init', array($this, 'tinymce_init') );
		
		//New hooks to register OUR codemirror library, not any other from different plugin!	
		add_action('wp_enqueue_scripts', array($this, 'register_codemirror'), 999999);
		add_action('admin_enqueue_scripts', array($this, 'register_codemirror'), 999999);
		//This hook used to load a codemirror bugfixes for prevent some plugin uses a very old versions of CodeMirror
		//add_action('wp_enqueue_scripts', array($this, 'codemirror_compat'), 999999);
		//add_action('admin_enqueue_scripts', array($this, 'codemirror_compat'), 999999);
	}

	public function codemirror_compat() {
		global $wp_scripts;
		if(!empty($wp_scripts->queue)) {
			foreach($wp_scripts->queue as $handle) {
			
			}
		}
		//\Pure\Debug::dump($wp_scripts); exit();
	}

	public function deregister_codemirror() {
		wp_deregister_script('codemirror');
		wp_deregister_style('codemirror');
		wp_deregister_script('codemirror_php');
    		wp_deregister_script('codemirror_javascript');
    		wp_deregister_script('codemirror_css');
    		wp_deregister_script('codemirror_xml');
    		wp_deregister_script('codemirror_clike');
    		wp_deregister_script('codemirror_dialog');
    		wp_deregister_script('codemirror_search');
    		wp_deregister_script('codemirror_searchcursor');
   		wp_deregister_script('codemirror_mustache');
	}

	public function register_codemirror() {
		$codemirror_root_url = PM()->plugin_dir_url() . '/js/CodeMirror/';
			
		$this->deregister_codemirror();
		wp_register_style('codemirror',  $codemirror_root_url . 'lib/codemirror.css');
		wp_register_script('codemirror', $codemirror_root_url . 'lib/codemirror.js');
		wp_register_style('codemirror_docs', $codemirror_root_url . 'doc/docs.css');
		

		wp_register_script('codemirror_addon_matchbrackets', $codemirror_root_url . 'addon/edit/matchbrackets.js');
		wp_register_script('codemirror_htmlmixed', $codemirror_root_url . 'mode/htmlmixed/htmlmixed.js');
		wp_register_script('codemirror_xml', $codemirror_root_url . 'mode/xml/xml.js');
		wp_register_script('codemirror_javascript', $codemirror_root_url . 'mode/javascript/javascript.js');
		wp_register_script('codemirror_css', $codemirror_root_url . 'mode/css/css.js');
		wp_register_script('codemirror_clike', $codemirror_root_url . 'mode/clike/clike.js');
		wp_register_script('codemirror_php', $codemirror_root_url . 'mode/php/php.js');
	}
	/*
	 * Thsi hook used because our plugin conflicts with huge wordpress plugin Wp-Editor:
	 * https://wordpress.org/plugins/wp-editor/
	 * **/
	public function wp_editor_fix() {
		
	}
	public function set_content_css($content_css) {
		$this->_content_css = $content_css;
		return $this;
	}
	public function get_content_css() {
		if(empty($this->_content_css)) {
			if($this->settings()->get('detect_content_css', false)) {

				if(file_exists(get_template_directory() . '/css/content.css')) {
					$this->set_content_css(get_template_directory_uri() . '/css/content.css');
				}	
			}
		}
		return $this->_content_css;
	}
	public function modal_message($message, $exit = true) {
		$this->view()->render("common/modal_message.php", array('message' => $message));
		if($exit) exit();
	}
	public function modal_close($exit = true) {
		$this->view()->render("common/modal_close.php", array());
		if(true == $exit)
			exit();
	}
	public function admin_head_code_snippet() {
		$ajax_url = admin_url('admin-ajax.php');
		echo "<script type=\"text/javascript\">var ajax_url = '{$ajax_url}'</script>\n";
		return;
	}
	public function maybe_enqueue_editor_files() {
		//wp_enqueue_script('SimplePHPBlocks', PM()->plugin_dir_url() . '/js/SimplePHPBlocks.js', array('jquery'));
		//wp_enqueue_style('PM#InlineEditor#style', PM()->plugin_dir_url() . '/css/')
	}
	/*
	 * This method just call a extended method to check if we can edit some file in specified path
	 * */
	public function can_edit_file($file_path) {
		return PM()->can_edit_file($file_path);
	}

	public function append_codemirror($content) {
		//Very nice!!!! Lol
		preg_match('/wp\-(.*?)\-editor-container/', $content, $matches);
		$editor_id = $matches[1];	
		ob_start();
		include __DIR__ . '/templates/codemirror_editor_block.php';	
		$content .= ob_get_clean();
		return $content;
	}

	public function tinymce_init($config) {
		$config['remove_linebreaks'] = false;
		$config['protect'] = '';
		$config['wpautop'] = false;
		//This will remove automatic tags adding to a children text nodes or something else...
		$config['forced_root_block'] = '';
		//
		$config['allow_html_in_named_anchor'] = true;
		$config['convert_fonts_to_spans'] = false;
		//valid_elements, extended_valid_elements Need to look to this config variables, because its important
		$config['fix_list_elements'] = false;
		$config['force_hex_style_colors'] = false;
		$config['invalid_elements'] = '';
		$config['invalid_styles'] = '';
		
		$config['schema'] = 'html5';
		if($this->get_content_css()) {
			$config['content_css'] = $this->get_content_css();
		}
		//$valid_elements = '@[id|class|title|style|onmouseover],div,h1,h2,h3,h4,a[name|href|target|title|alt],#p,blockquote,ol,ul,li,br,img[src|height|width],sub,sup,b,i,u,-span[data-mce-type],hr,span,i,em';	
		//Add more HTML5 elements
		//$valid_elements .= ',section,article,strong,b,iframe,frame';
		$valid_elements = "*[*]";
		$config['valid_elements'] = $valid_elements;
		$config['extended_valid_elements'] = "*[*]";
		return $config;
	}

	public function tinymce_buttons($buttons) {
		//array_push($buttons, 'mycode');
		return $buttons;
	}

	public function register_tinymce_plugins() {	
		$plugins_array = array(	
			'SimplePHPBlocks' => PM()->plugin_dir_url() . '/js/tinymce/SimplePHPBlocks/SimplePHPBlocks.js',
			//'PMFixEmptyElements' => PM()->plugin_dir_url() . '/js/tinymce/Fixes/PMFixEmptyElements/plugin.js',
			//'PMFixNoElements' => PM()->plugin_dir_url() . '/js/tinymce/Fixes/PMFixNoElements/plugin.js'
		);
		return $plugins_array;
	}

	public function ajax_error_response($message = "", $status_code = 1) {
		if(empty($message)) {
			$message = "Sorry, but unspecified error occured while your request :(";
		}
		$response = array(
			'status' => intval($status_code),
			'message' => $message
		);
		echo json_encode($response);
		exit();
	}
	public function ajax_configure_widget() {
		$widget = @$_REQUEST['widget'];
		$call_code = @$_REQUEST['call'];
		if(empty($widget)) {
			return $this->ajax_error_response(__("You must provide a valid widget name.", WAMT_DOMAIN));
		}
		if(empty($call_code)) {
			return $this->ajax_error_response(__("Seems call widget code is empty. Could not find widget call source code. Terminating.", WAMT_DOMAIN));
		}
		$call_code = \Pure\Helper\Data::decode($call_code);
		$call_code = unserialize($call_code);
		$widget = \Pure\Helper\Data::decode($widget);
		if(!class_exists($widget, true)) {
			return $this->ajax_error_response(sprintf(__("Seems ``%s`` not a valid widget.", WAMT_DOMAIN), esc_html($widget)));
		}
		$filepath = $call_code['file'];
		if(!\Pure\Helper\FS::has_server_root($filepath)) {
			return $this->ajax_error_response(sprintf(__("Seems path ``%s`` not in server root directory.", WAMT_DOMAIN), esc_html($filepath)));
		}

		$code_finder = new \Pure\Widget\Settings\CodeFinder($widget, $filepath, $call_code['line'], $call_code['args'][1]);
		$widget_call = $code_finder->get_widget_call();	
		if(!is_object($widget_call)) {
			return $this->ajax_error_response(__("This function is temporary unavailable for in-post editor. Please, be patient, and try later."));
		} 
		$settings_container = \Pure\Helper\Widget::get_widget_settings_container($widget_call);
		//\Pure\Debug::dump($settings_container)	;
		if($this->request()->is_post_request()) {
			$new_settings = PM()->request()
				->post()
				->get_all();
			//FIXME: Seems here we need to DROP some non-existent arguments?
			unset($new_settings['save']);
			unset($new_settings['submit']);
			try {
				\Pure\Helper\Widget::save_call_settings($widget_call, $new_settings);
				$this->close_modal(true);
				exit();
			} catch(\Pure\Exception $e) {
				throw $e;	
			}
		}
		$this->set_title(__("Widget settings", WAMT_DOMAIN));

		$this->dispatch("manage/widget_settings.php", array(
			'settings_container' => $settings_container,
			'widget_name' => $widget_call->get_widget_name()
		));
		/*if(empty($settings_array)) {
			return $this->ajax_error_response(__("We could not find a Widget call. Some internal error occured, or this widget does not have any settings."));
		}*/
		exit();	
	}
	public function ajax_save_code() {
		$file_in_editor = @$_POST['pm_file_in_editor'];
		if(empty($file_in_editor)) {
			return $this->ajax_error_response("Error occured, the file not specified!", 1);
		}
		$file_in_editor = PM()->clean_user_filepath($file_in_editor);
		//Force file pathes to root which we have...
		if(!$this->can_edit_file($file_in_editor)) {
			return $this->ajax_error_response("Sorry, but you can not edit this file! This incident will be reported!", 2);
		}
		$code = stripslashes_deep($_POST['code']);
		//FIXME: Maybe this actions need a more checks???!!!
		if(!is_writable($file_in_editor)) {
			return $this->ajax_error_response("Sorry, but this file is not available for writing by our scripts.", 3);
		}
		//FIXME: We need to save a backup.
		file_put_contents($file_in_editor, $code);
		$reponse = array(
			'file_in_editor' => $file_in_editor,
			'status' => 0,
			'code' => $code
		);
		echo json_encode($reponse);
		exit();		
	}

	public function ajax_convert_code() {
		$code = @$_POST['code'];
		$code = stripslashes_deep($code);	
		$to = @$_POST['to'];
		if(empty($code)) {
			echo json_encode(array('status' => 0, 'result' => $code));
			exit();
		}	
		if($to == 'visual') {
			$_ = new \PureLib\CodeParser($code);
			$code = $_
					->parse()
					->to_visual();
			echo json_encode(array('status' => 0, 'result' => $code));
			exit();
		} else {
			//TODO:
		}
		exit();
	}
	public function snippet_edit_action() {
		//Add codemirror plugin here
		add_filter('mce_external_plugins', array( $this, 'register_tinymce_plugins' ));
		add_filter('mce_buttons', array( $this, 'tinymce_buttons' ) );
		//We really need to use a print scripts, instead a enquue hook, because our register and deregister actions must be runned first
		add_action('admin_print_scripts', array($this, 'require_scripts'));
		add_action('admin_print_styles', array($this, 'print_styles'));
		$force_directories = true;
		$file = @$_REQUEST['file'];
		$file = \Pure\Helper\Data::decode(str_replace("\0", "", $file));	
		$file_path_encoded = self::encode_filepath($file);
		if(empty($file)) {
			//TODO: Need to show an error message
			return;
		}
		$found = false;
		foreach(PM()->get_snippets_root_directories() as $directory) {
			if(false !== strpos($file, $directory)) {
				$found = true;
				break;	
			}
		}
		if(!$found) {
			//TODO: Need to show access violation message!
			return;
		}
		$editor_id = "file_" . md5($file);
		remove_filter('the_content', 'wpautop');
		$content = file_get_contents($file);	
		define( 'IFRAME_REQUEST', true );
		@header('Content-Type: ' . get_option('html_type') . '; charset=' . get_option('blog_charset'));

		wp_enqueue_script('PMUtils', PM()->plugin_dir_url() . '/js/utils.js');
		wp_enqueue_script('SimplePHPBlocks', PM()->plugin_dir_url() . '/js/SimplePHPBlocks.js', array('jquery'));
		wp_enqueue_script('tiny_mce');
		//add_action('tiny_mce_before_init', )
		$this->view()->render("editor.php", array(
			'editor_id' => $editor_id,
			'content' => $content,
			'file' => $file,
			'file_path_encoded' => $file_path_encoded
		));
		exit();

	}
	public function ajax_copy_template() {
		if(empty($_REQUEST['widget']) || empty($_REQUEST['template'])) {
			return $this->modal_message(__("The source widget or template not specified!"));	
		}	
		$widget_name = \Pure\Helper\Data::decode($_REQUEST['widget']);
		$template_name = \Pure\Helper\Data::decode($_REQUEST['template']);
		$finder = new \Pure\Template\Finder($widget_name, $template_name);
		$template = $finder->get_template();	
		if(!is_object($template)) {
			return $this->modal_message(__("Can not find a source template for copyeing."));
		}	
		$current_wp_theme = wp_get_theme();
		$current_theme_directory = $current_wp_theme->get_template_directory();
		$new_template_name = "{$template_name}_new";
		$new_finder = new \Pure\Template\Finder($widget_name, $new_template_name);
		$new_template = $new_finder->create_template();
		$this->set_title(__("Copy template", WAMT_DOMAIN));	
		if(PM()->request()->is_post_request()) {
			$new_template_name = \Pure\Helper\Data::clear_path_var($_POST['pm_new_template_name']);
			$_new_finder = new \Pure\Template\Finder($widget_name, $new_template_name);
			$new_template = $_new_finder->create_template();		
			if($new_template->exists()) {
				return $this->modal_message(sprintf(__("This Widget template <strong>%s</strong> is already exists. Please, specify other template name. Terminating."), $new_template->get_template_directory()));
			}	
			try {
				$result = \Pure\Helper\Template::copy_template($template, $new_template);

				PM()->module("FS")->track_new_files($new_template->get_template_directory());

				return $this->modal_close();

			} catch(\Exception $e) {
				return $this->modal_message($e->getMessage());
			}

			$this->view()->render('common/modal_close.php', array());	
			exit();
		}	
		$this->dispatch('manage/copy_template.php', array(
			'template' => $template,
			'template_name' => $template_name,
			'widget_name' => $widget_name,
			'current_wordpress_theme' => $template->get_theme_name(),
			'destination_directory' => $current_theme_directory,
			'new_template_name' => $template_name . "_new",
			'new_template' => $new_template
		));	
		exit();
	}
	public function require_scripts() {
		wp_enqueue_script('codemirror');
		

		wp_enqueue_script('codemirror_addon_matchbrackets');
		wp_enqueue_script('codemirror_htmlmixed');
		wp_enqueue_script('codemirror_xml');
		wp_enqueue_script('codemirror_javascript');
		wp_enqueue_script('codemirror_css');
		wp_enqueue_script('codemirror_clike');
		wp_enqueue_script('codemirror_php');
		
	}
	public function print_styles() {
	
		wp_enqueue_style('codemirror');

		wp_enqueue_style('codemirror_docs');
	}
	public function view() {
		return PM()->view();	
	}
	public static function encode_filepath($path) {
		return PM()->encode_filepath($path); 
	}
	public static function decode_filepath($path) {
		return PM()->decode_filepath($path);
	}
}
