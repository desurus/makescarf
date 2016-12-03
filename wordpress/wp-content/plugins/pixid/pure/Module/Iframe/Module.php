<?php
/**
 * This module provides a basic methods and accessebility to easy display a parts for IFrame page requests, such as:
 * header()
 * footer()
 * etc.
 * @author Shell
 * @version 0.1
 * FIXME: This module at this moment contains a really basic solutions, seems we need to store a template part files in a dedicated directories, not in a "global" template directory.
 * */

namespace Pure\Module\Iframe;
class Module extends \Pure\Module {
	protected function _init() {
		if($this->settings()->get("track_iframe_template", true)) {
			add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
		}	
	}

	public function enqueue_scripts() {
		wp_enqueue_script('pm-auto-iframe-size', PM()->plugin_dir_url() . '/js/pm-auto-iframe-size.js');	
	}

	public function header($params = array()) {
		//if(!defined('IFRAME_REQUEST')) define('IFRAME_REQUEST', true);
		if(!($params instanceof \PureLib\Config\RawArray)) {
			$params = new \PureLib\Config\RawArray($params);
		}
		$auto_resize = $params->get('auto_resize', true);
		if($auto_resize) {
			wp_enqueue_script('pm-auto-iframe-size', PM()->plugin_dir_url() . '/js/pm-auto-iframe-size.js');
		}
		echo $this->view()->fetch('iframe/header.php', compact('auto_resize'));
	}
	public function footer() {
		echo $this->view()->fetch('iframe/footer.php');
	}
}
