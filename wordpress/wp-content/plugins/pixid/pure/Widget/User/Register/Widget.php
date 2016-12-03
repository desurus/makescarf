<?php
/**
 * This widget used to display a registration forms.
 * And process a registrations.
 * @author Shell
 * @version 0.0.1-dev
 * TODO: Need a small documentation list in header.
 * */

namespace Pure\Widget\User\Register;

use \PureLib\Form as Form;

class Widget extends \Pure\Widget {
	protected $_form;
	public function get_form() {
		if(!is_object($this->_form)) 	{
			$form = new \PureLib\Form\Form(
				array(
					'action' => '',
					'method' => 'post'
				)
			);
			$form->add_element(new Form\Element\Text(
				array(
					'name' => 'user_login',
					'placeholder' => __('Login'),
					'label' => __('Login'),
					'required' => true
				)
			))
				->add_element(new Form\Element\Email(
					array(
						'name' => 'user_email',
						'placeholder' => __('Email'),
						'label' => __('Email address'),
						'required' => true
					)
				))
				->add_element(new Form\Element\Password(
					array(
						'name' => 'user_password',
						'placeholder' => __('Password'),
						'label' => __('Password'),
						'required' => true
					)
				))
				->add_element(new Form\Element\Password(
					array(
						'name' => 'user_password_confirm',
						'placeholder' => __('Confirm password'),
						'label' => __('Confirm password'),
						'required' => true
					)
				))
				->add_element(new Form\Element\Hidden(
					array(
						'name' => 'do_register',
						'value' => 'yes'
					)
				));
			$this->_form = $form;
		}
		return $form;
	}
	/**
	 * A main widget method which displays the form from template, and working on provided data after post request.
	 * TODO: The main worker on request code can be separated to dedicated method, because we can have a different usage usecase for this code of user registration.
	 * TODO: We can set a error messages from arguments to this widget.
	 * TODO: The user required fields can be a little more flexible, at all seems we can require only an email address from a user, and build other data "on the fly", or just require little later.
	 * TODO: Reset password after submission
	 * TODO: Configure all fields.
	 * TODO: Configure advanced fields which can be saved directly in wordpress usermeta table.
	 * */
	public function widget() {
		$form = $this->get_form();
		$errors = array();
		$error_string = array();
		if(@$_SESSION['registered']) {
			return $this->display_template('success.php');
		}
		if($this->request()->is_post_request() && $this->request()->get_request_post()->get('do_register') == 'yes') {
			$post = $this->request()->get_request_post(false);	
			//TODO: Trim all data?
			//TODO: Htmlspecialchars to all?
			$user_login = $post->get('user_login', '');	
			if(empty($user_login)) {
				$errors[] = __('Please specify a valid and not empty user login.');
			}	
			$user_email = $post->get('user_email', '');
			if(empty($user_email)) {
				$errors[] = __('Please specify a valid and not empty user email.');
			}
			$user_password = $post->get('user_password', '');
			if(empty($user_password)) {
				$errors[] = __('Please, specify a valid and not empty password.');
			}
			$user_password_confirm = $post->get('user_password_confirm', '');
			if(empty($user_password_confirm) || $user_password != $user_password_confirm) {
				$errors[] = __('Entered passwords mismatched, please, fix this issue and try again.');
			}
			
			
			if(empty($errors)) {
				//Ok, proceed advanced checking
				$user = get_user_by('login', $user_login);
				if(($user instanceof \WP_User)) {
					$errors[] = __('User with this login already exsists. Please use another one. Or retrieve a password.');
				} else {
					$user = get_user_by('email', $user_email);
					if(($user instanceof \WP_User)) {
						$errors[] = __('User with this email is already registered on this website.');
					}	
				}

			}
			if(empty($errors)) {
				$userdata = array(
					'user_login' => $user_login,
					'user_email' => $user_email,
					'user_pass' => $user_password
				);
				$result = wp_insert_user($userdata);
				$result = true;
				if(($result instanceof \WP_Error)) {
					foreach($result->get_error_messages() as $message) {
						$errors[] = $message;
					}
				}	
				if(empty($errors)) {
					$_SESSION['registered'] = true;
					$redirect_url = $_SERVER['REQUEST_URI'];	
					wp_redirect($redirect_url, 303);	
					exit();	
				}
			}
			$form->set_values($post->to_array());
			
		}
		$this->display_template(compact('form', 'errors'));
	}
}
