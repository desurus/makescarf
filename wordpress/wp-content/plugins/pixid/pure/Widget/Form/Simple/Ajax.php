<?php

namespace Pure\Widget\Form\Simple;

use \Pure\Response as Response;

class Ajax extends \Pure\Module\Ajax\Handler {
	public function send_form_action() {
		$data = $this->get_request()->get_request_post();
		$form = $this->widget()->get_form();
		$widget_args = $this->widget()->args();
		if(!empty($data)) {
			$form->set_values($data->to_array());
		}
		//Small check
		$errors = array();
		foreach($form->get_elements() as $element) {
			if($element->get_option('required', false)) {
				$v = $element->get_value();
				if(empty($v)) {
					$errors[] = sprintf(__("The \"%s\" is required in this form. Please fill it.", WAMT_TEXTDOMAIN), $element->get_option('title'));
					continue;
				}
			}
			if($element->get_option('type') == 'email') {
				if(!is_email($element->get_value())) {
					$errors[] = sprintf(__("You have entered not a valid email address in field \"%s\".", WAMT_TEXTDOMAIN), $element->get_option('title'));
				}
			}
		}
		if(!empty($errors)) {
			$response = new Response\JSON(Response\JSON::CODE_ERROR, implode('<br>', $errors), array('errors' => $errors));
			$response->send(true);
		}
		$mail_to = $widget_args->get('send_to', '');
		if(empty($mail_to)) {
			$mail_to = $widget_args->get('mail_to', '');
		}
		if(empty($mail_to)) {
			$mail_to = get_option('admin_email');
		}
		if(is_string($mail_to)) {
			$mail_to = explode(',', $mail_to);
			$mail_to = array_map('trim', $mail_to);
		}
		
		$subject = $widget_args->get('mail_subject');
		$body = "";
		$mail_template = $widget_args->get('mail_template', '');
		//FIXME: We need to support not only text and valuable inputs, we can here get an arrays|files and other useful things when needed.
		if(empty($mail_template)) {
			foreach($form->get_elements() as $element) {
				$title = $element->get_option('mail_title');
				if(empty($title)) {
					$title = $element->get_option('title');
				}
				if(empty($title)) {
					$title = $element->get_option('label');
				}
				if(empty($title)) {
					$title = $element->get_option('placeholder');
				}
				$body .= "{$title}: {$element->get_value()}\n";
			}
		} else {
			//TODO: We need dedicated functionality to parse templates like this
			$replaces = array();
			foreach($form->get_elements() as $element) {
				$value = $element->get_value();
				$name = $element->get_name();
				//Append unescaped data to template variables
				$replaces["##{$name}##"] = $value;	
				//Append escaped data to template variables
				$replaces["#{$name}#"] = strip_tags($value);
			}
			$body = str_replace(array_keys($replaces), array_values($replaces), $mail_template);	
		}
		//TODO: We need to control the sended mail format...
		//TODO: We need an usefull argument such as, Add-Reply-To header template, or something like this, maybe automatic detection of reply-to address and name?
	
		if($widget_args->get('automatic_signature', true)) {
			$body .= "\n==============\n";
			$date = date('d.m.Y H:i:s');
			$body .= sprintf(__("This message is automatically generated at %s. Do not reply to it.", WAMT_TEXTDOMAIN), $date);
		}
		foreach($mail_to as $email) {
			wp_mail($email, $subject, $body);
		}	
		$success_message = $widget_args->get('success_message', __("Your message successfully sended!", WAMT_TEXTDOMAIN));
		$response = new Response\JSON(Response\JSON::CODE_SUCCESS, $success_message);
		$response->send(true);
	}
}
