<?php
/**
 * This Widget at this moment have a very basic functionality to provide a simple login form to a users.
 * The old code which not uses a \PureLib\Form object for display form will be deprecated soon.
 * In this version we have a doubled code for display form in new valid way (with \PireLib\Form) and the old way which uses some "hand-made" form declaration.
 * @version 0.0.12
 * @author Shell
 * */
namespace Pure\Widget\User\Login;

class Widget extends \Pure\Widget {
	//We store an initialized obejct for form, to prevent multiple initialization... Maybe it's a useless, but anyway a good practice.
	//Note: It's not a static property or variable.
	protected $_form;
	/**
	 * This method return an initialized object of \PureLib\Form class for login form
	 * @param array $data TODO: This array can contain a current values of data...
	 * @return \PureLib\Form\Form
	 * */
	public function get_form($data = array()) {
		if(!is_object($this->_form)) {
			$form = new \PureLib\Form\Form(array(
				'name' => 'login_form',
				'id' => 'login_form',
				'class' => 'login_form'		
			));
			$form->add_element('text', 'user_login', array(
				'placeholder' => $this->tr('Login or email'),
				'label' => $this->tr('Login')
			))
				->add_element('password', 'user_password', array(
					'placeholder' => $this->tr('Your password'),
					'label' => $this->tr('Password')
				))
				->add_element('hidden', 'redirect_url', array(
				
				))
				->add_element('hidden', 'do_login', array(
					'value' => 'yes'
				));
			$this->_form = $form;
		}
		return $this->_form;
	}
	public function widget() {
		$errors = array();
		$redirect_url = $this->args()->get('redirect_url');
		if(empty($redirect_url)) {
			$redirect_url = urldecode($this->request()->get('redirect_url'));
		}
		$form = $this->get_form();
		$form->set_value('redirect_url', $redirect_url);
		if('yes' == $this->request()->get('logout', '') && is_user_logged_in()) {
			wp_logout();
			PM()->Frontend()->redirect($redirect_url);
			exit();
		}
		if( $this->get_request()->is_request_post() && $this->get_request()->get_request_post()->get('do_login', '') == 'yes') {
			$request = $this->request();
			$login = $request->get('user_login');
			$password = $request->get('user_password');
			if(empty($login) || empty($password)) {
				$errors[] = $this->tr('Login or password can not be empty');
			}
			if(empty($errors)) {
				$result = wp_authenticate($login, $password);
				if(is_wp_error($result)) {
					$errors[] = $this->tr('Wrong username or password'); 
				} elseif($result instanceof \WP_User) {
					wp_set_auth_cookie($result->ID);
					$location = $redirect_url; 
					PM()->Frontend()
						->redirect($redirect_url);	
				}
			}
		}
		$hidden_fields = array();
		$hidden_fields['redirect_url'] = "<input type=\"hidden\" name=\"redirect_url\" value=\"{$redirect_url}\">";
		$hidden_fields['do_login'] = '<input type="hidden" name="do_login" value="yes">';
		if(is_user_logged_in()) {	
			$this->display_template('logged_in.php');
		} else {
			$this->display_template(compact('errors', 'redirect_url', 'hidden_fields', 'form'));	
		}
	}
}
