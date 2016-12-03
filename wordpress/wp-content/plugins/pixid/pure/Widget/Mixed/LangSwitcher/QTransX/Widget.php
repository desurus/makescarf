<?php

namespace Pure\Widget\Mixed\LangSwitcher\QTransX;

class Widget extends \Pure\Widget\Internal {
	public function widget() {
		if(!function_exists('qtranxf_getSortedLanguages')) {
			return $this->error_box("This widget require qTranslate-x plugin installed and enabled.");
		}
		$languages = qtranxf_getSortedLanguages();
		if(empty($languages))	return;
		$_ = array();
		foreach($languages as $lang) {
			$lang = array(
				'code' => $lang,
				'url' => qtranxf_convertURL('', $lang)
			);	
			$_[] = $lang;
		}
		$languages = $_;
		$this->display_template(compact('languages'));
	}
}
