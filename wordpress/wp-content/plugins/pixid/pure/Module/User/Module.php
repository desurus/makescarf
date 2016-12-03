<?php
/**
 * This module is used for users manipalution changes in Wordpress.
 * FIXME: Todo something with no-email users. Seems we can to create some random email with a @local part. 
 * @author Shell
 * @version 0.0.21
 * */
namespace Pure\Module\User;
class Module extends \Pure\Module {
	protected function _init() {
	
	}
	/**
	 * */
	/*
	 * Just a wrapper on wordpress function 
	 * */
	public function get_current_user() {
		return wp_get_current_user();
	}
	/*
	 * Method get a current logged in user ID or FALSE if no user is logged in
	 * @return mixed **/
	public function get_current_user_ID() {
		if(!is_user_logged_in()) return false;
		$user = $this->get_current_user();
		return $user->data->ID;
	}
	/**
	 * This method tries to create a new user, based on provided userdata array.
	 * The only required field here is a user_email which can be dropped later, and register users without any data, just for temporary their logging-in.
	 * @param array $available_userdata
	 * @return int $user_id
	 * @throws \Pure\Exception if something goes really wrong :D
	 * **/
	public function register($available_userdata = array()) {
		//if($this->settings()->get("random_email"))	
		if(empty($available_userdata['user_login'])) $available_userdata['user_login'] = $available_userdata['user_email'];
		if(empty($available_userdata['user_pass'])) $available_userdata['user_pass'] = wp_generate_password();
		$userdata = array(
			'user_email' => $available_userdata['user_email'],
			'user_login' => $available_userdata['user_login'],
			'user_pass' => $available_userdata['user_pass']
		);
		$user_id = wp_insert_user($userdata);
		//FIXME: Need todo something if returned value is WP_Error
		if(is_wp_error($user_id)) {
			throw new \Pure\Exception("Something goes wrong, wp_insert_user returned an WP_Error object. Error: " . $user_id->get_error_message() );
		}
		return $user_id;
	}
	/**
	 * This method checks if current user is logged in.
	 * Just a wrapper for wordpress internal function is_user_logged_in() 
	 * @see is_user_logged_in()
	 * */
	public function is_logged_in() {
		return is_user_logged_in();
	}
	/**
	 * At this moment this is just a wrapper on wp_set_auth_cookie Wordpress function.
	 * @param mixed $user An user which will be set as a current.
	 * @param boolean $remember
	 * @param boolean $secure
	 * */
	public function set_auth_cookie($user, $remember = true, $secure = true) {
		return wp_set_auth_cookie($user->ID, $remember, $secure);
	}
}
