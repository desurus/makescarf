<?php

namespace Pure\Widget\Mixed\LangSwitcher;
class Widget extends \Pure\Widget {
	public function get_current_language() {
		if(function_exists('qtranxf_getLanguage')) {
			$lang = qtranxf_getLanguage();
			return $lang;
		}
	}
	public function get_languages() {
		$languages = array();
		if(function_exists('qtranxf_getSortedLanguages')) {
			$_languages = qtranxf_getSortedLanguages();
			foreach($_languages as $lang) {
				$languages[$lang] = qtranxf_getLanguageNameNative($lang);
			}
		}
		return $languages;
	}
	public function get_language_native_title($lang_code) {
		if(function_exists('qtranxf_getLanguageNameNative')) {
			return qtranxf_getLanguageNameNative($lang_code);
		}
	}
	public function get_current_url_language($lang_code) {
		if(function_exists('qtranxf_convertURL')) {
			return qtranxf_convertURL('', $lang_code);
		}
	}
	public function widget() {
		$current_language = $this->get_current_language();
		$languages = $this->get_languages();	
		$language = array(
			'code' => $current_language, 
			'title' => $this->get_language_native_title($current_language)
		); 
		$switcher = array();
		foreach($languages as $language_code => $language_title) {
			$current = false;
			if($language_code == $current_language) $current = true;
			$item = array(
				'code' => $language_code,
				'title' => $language_title,
				'url' => $this->get_current_url_language($language_code),
				'current' => $current
			);
			$switcher[] = $item;
		}
		$this->display_template(compact('language', 'languages', 'switcher'));	
	}
}
