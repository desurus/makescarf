<?php

/**
 * Current Widget for display a current page pathes like a "breadcrumb"
 * At current version this widget uses a breadcrumb-navxt plugin as "backend".
 * @version 0.1
 * @author Shell
 * */
namespace Pure\Widget\Breadcrumb;
class Widget extends \Pure\Widget {
	public function widget() {
		if(!class_exists('breadcrumb_navxt')) return $this->error_box(__("For use breadcrumb widget, you must install breadcrumb-navxt plugin. Check it here: https://ru.wordpress.org/plugins/breadcrumb-navxt/"));
		global $breadcrumb_navxt;
		if(!is_object($breadcrumb_navxt)) return $this->error_box(__("Something goes wrong. Global breadcrumb object not initialized."));
		$this->display_template();	
	}
}
