<?php

/**
 * This class provide a little bit easy manipulation with wp_customizer object.
 * Instead of provide a lot of calls to add few settings to Wordpress customizer instance.
 * TODO: We need to add a separate class which provide a methods to call settings set directly somewhere in theme class, Not directly in customizer call hook. Task #
 * @author Shell
 * @version 0.0.1
 * */
namespace PureLib\Wordpress;
class Customizer {
	protected $_customizer;
	public function __construct($wp_customizer) {	
		$this->set_customizer($wp_customizer);
	}

	/**
	 * Just sets a custiomizer object for this instance. All other methods will work directly with this object.
	 * @param \WP_Customize_Manager $wp_customizer
	 * @return \PureLib\Wordpress\Customizer
	 * */
	public function set_customizer($wp_customizer) {
		if(!($wp_customizer instanceof \WP_Customize_Manager)) throw new \Exception("You must provide a valid customizer object.");
		$this->_customizer = $wp_customizer;
		return $this;
	}
	/**
	 * Method just return an object which set as a Custimizer. Must be a valid instance of \WP_Customizer.
	 * @return \WP_Customizer
	 * */
	public function get_customizer() {
		return $this->_customizer;
	}
	/**
	 * Add a new section to wordpress customizer screen
	 * @param string $name The sanitized name of section
	 * @param string $title User friendly title for section.
	 * @param string $description Small description for your section
	 * @param array $args Maybe an advanced arguments which will be provided directly to customizer method ::add_section call.
	 * @return \PureLib\Wordpress\Customizer
	 * */
	public function add_section($name, $title, $description, $args = array()) {
		$customizer = $this->get_customizer();
		$arguments = array(
			'title' => $title,
			'description' => $description,
			'capability' => 'edit_theme_options',
			'theme_supports' => '',
			'priority' => null
		);
		$arguments = wp_parse_args($arguments, $args);
		$customizer->add_section($name, $arguments);
		return $this;
	}
	/**
	 * TODO: Doc
	 * */
	public function add_option($section, $type, $name, $args = array()) {
		$defaults = array(
			'title' => '',
			'default' => '',
			'description' => '',
			'input_attrs' => array()
		);
		$arguments = wp_parse_args($args, $defaults);
		$customizer = $this->get_customizer();
		$customizer->add_setting($name, array(
			'default' => $arguments['default']
		));
		$customizer->add_control($name, array(
			'type' => $type,
			'section' => $section,
			'settings' => $name,
			'label' => $arguments['title'],
			'description' => $arguments['description'],
			'input_attrs' => $arguments['input_attrs']
		));
	}
}
