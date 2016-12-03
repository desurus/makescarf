<?php

/**
 * TODO: Add a short description...
 * @version 0.0.2
 * @author Shell
 * */

namespace Pure\Widget\Social\Facebook\Login;
class Widget extends \Pure\Widget {
	public function widget() {
		ob_start();
		do_action('facebook_login_button');
		$c = ob_get_clean();
		echo $c;
		return;
		$app_id = $this->args()->get('app_id', '');
		$redirect_uri = home_url() . add_query_arg('fb_auth', 'yes', $_SERVER['REQUEST_URI']);
		$redirect_uri = urlencode($redirect_uri);
		$url = "https://www.facebook.com/dialog/oauth?client_id={$app_id}&redirect_uri={$redirect_uri}";
		$this->display_template('link.php', compact('url'));	
	}
}
