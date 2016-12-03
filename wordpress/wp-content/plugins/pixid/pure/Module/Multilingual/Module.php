<?php
/**
 * This is a basic module which provide a very pure but usefull library functions and actions to develope a full quality multilingual Wordress websites
 * @author Shell
 * @version 0.0.1
 * At this moment this module support an automatic text|code|file snippets copieing and creation while frontend website language selects.
 * */
namespace Pure\Module\Multilingual;
class Module extends \Pure\Module {
	protected function _init() {
		if(!$this->_can_multilanguage()) {
			//Prevent any initialization if this website can not do things with multilanguages.
			//i.e. no any supported multilanguage plugins are enabled.
			return;
		}
		add_filter('wamt_snippet_path', array($this, 'snippet_path'), 10, 3);
	}
	

	/**
	 * This filter used to track fetched snippets. It's parse a path to file and...
	 * TODO: Doc this method.
	 * TODO: We need a little other variant of files location. i.e. in the separate snippets directories. But it need a full copy of directories structure... So...
	 * */
	public function snippet_path($path, $params, $args) {
		$current_lang = $this->get_current_language();	
		$s = $this->settings();
		//We do not do anything if the current language is the same as a original snippets language
		if($s->get('snippets_locale') == $current_lang) {
			return $path;
		}
		if(!$s->get('auto_snippets', true)) return $path;
		
		$basename = basename($path);
		$basedir = dirname($path);
		if(false !== strpos($basename, $current_lang)) return $path;//Snippet is already translated and we use translated variant.
		$_ = explode('.', $basename);
		$_[0] = $_[0] . '_' . $current_lang;
		$t = trailingslashit($basedir) . implode('.', $_);
		if(!file_exists($t)) {
			$r = @copy($path, $t);		
			if(!$r) { 
				//TODO: Maybe show some error?
				return $path;
			}
			PM()->FS()->track_new_nodes(array( new \PureLib\FS\Node\File($t)));
		}	
		return $t;
	}

	protected function _can_multilanguage() {
		$can = false;
		if(function_exists('qtranxf_init_language')) return true;
		if(function_exists('pll__')) return true;
		return true;
	}
	public function get_current_language_code() {
		return get_bloginfo('language');
	}
	/**
	 * This method a "current" seelcted language in fronend.
	 * At this moment this method supports only qTranslate-x plugin
	 * @return string $lang Return a code of currently selected language.
	 * */
	public function get_current_language() {
		return get_bloginfo('language');	
	}

}
