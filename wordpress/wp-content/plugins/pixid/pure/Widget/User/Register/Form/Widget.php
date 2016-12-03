<?php
/**
 * This is a very simple temporary widget to show and register wordpress users.
 * @version 0.0.1
 * @author Shell
 * */
namespace Pure\Widget\User\Register\Form;
class Widget extends \Pure\Widget {
	public function widget() {
		$redirect_url = "/";
		if($this->args()->get('redirect_url')) {
			$redirect_url = $this->args()->get('redirect_url');
		}
		if($this->request()->get('redirect_url')) {
			$redirect_url = $this->request()->get('redirect_url');
		}
		$form_data = array();
		if(is_user_logged_in()) {
			$this->display_template('logged_in.php');
		}
		$errors = array();
		$messages = array();
		if($this->request()->is_post_request()) {
			$data = $this->request()
					->get_post();
			$user_email = $data->get('user_email');
			if(empty($user_email)) {
				$errors[] = __("User email can not be empty. Please, set your valid email.");	
			} else {
				$_user = get_user_by('email', $user_email);
				if(is_object($_user) && $_user->ID != 0) {
					//FIXME: We can show a link to a forgot password page.
					$errors[] = __("User with this email is already exsits.");
				}
			}

			if(empty($errors)) {
				try {
					$user_id = PM()
						->User()
						->register(array(
							'user_email' => $user_email
						));
					$user = get_user_by('id', $user_id);
					if($this->args()->get('auto_login', true)) {
						PM()
							->User()
							->set_auth_cookie($user, true);		
					}
					if(!empty($redirect_url)) {
						Header("Location: {$redirect_url}");
						exit();
					}
				} catch(\Pure\Exception $e) {
					$errors[] = __('Something goes wrong, can not register!');
					PM()
						->Debug()
						->log_pm_exception($e);
				}

			}
		}
		$this->display_template(compact('redirect_url', 'form_data', 'errors'));
	}
}
