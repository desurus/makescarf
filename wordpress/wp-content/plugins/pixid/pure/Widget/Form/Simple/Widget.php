<?php

/**
 * This widget created just for a quick form declarations and ajax sendings.
 * @author Shell
 * @version 0.0.1
 * Current aurguments support list:
 * 
 * <strong>$fields</strong>
 * An array of fields which will be displayed in form. 
 * Format: 
 * 	array(
 *		'field_name' => array(
 *			'type' => 'type_of_the_field'//Default see @Form\Element namespace,
 *			'label' => 'The label of element',
 *			... And more field properties may goes here, detailed in Form\Element namespace ...
 *		)
 * 	)
 * <strong>$fill_test_data</strong>	
 * Boolean, this argument may been used for a quick for filling with a random test data, it's usefull in development environment.
 * <strong></strong>
 * */

namespace Pure\Widget\Form\Simple;

class Widget extends \Pure\Widget {
	public function get_form() {
		$fields = $this->args()->get('fields', array());
		if(empty($fields)) return;
		$form = new \PureLib\Form\Form(array(), array(
			'auto_submit' => false
		));
		foreach($fields as $name => $field) {
			if(empty($field['name'])) $field['name'] = $name;

			$form->add_element($field['type'], $field['name'], $field);
		}
		return $form;
	}
	public function widget() {
		$form = $this->get_form();	
		if($this->args()->get('fill_test_data', false)) {
			$elements = $form->get_elements();
			foreach($elements as $element) {
				if($element->get_option('type') == 'email')
					$element->set_option('value', 'test@test.com');
				else
					$element->set_option('value', uniqid());
			}
		}
		$this->display_template(compact('form'));
	}
}
