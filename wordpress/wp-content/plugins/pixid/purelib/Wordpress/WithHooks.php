<?php

/**
 * This class describe an abstract plugin or theme class which can be used to a quick access to usefull themes and plugin methods.
 * This class contains a basic methods, which can helps do describe a frequently-used hooks in plugins and themes.
 * @author Shell
 * @version 0.0.20
 * */
namespace PureLib\Wordpress;
abstract class WithHooks {
	public function __construct($args = array()) {
		$defaults = array(
			'hook_methods' => array(
				'init' => array(
					'methods' => array(
						'init',
						'wp_init'
					)
				),
				'wp_enqueue_scripts' => array(
					'methods' => array(
						'wp_enqueue_scripts',
						'enqueue_scripts'
					)
				),
				'customize_register' => array(
					'methods' => array(
						'customize_register'
					)
				),
				'after_setup_theme' => array(
					'methods' => array(
						'after_setup_theme'
					)
				),
				'body_class' => array(
					'methods' => array(
						'body_class'
					),
					'type' => 'filter'
				),
				'wp_head' => array(
					'methods' => array(
						'wp_head'
					)
				),
				'wp_footer' => array(
					'methods' => array(
						'wp_footer'
					)
				),
				'register_sidebars' => array(
					'methods' => array(
						'register_sidebars'
					)
				),
				'widgets_init' => array(
					'methods' => array(
						'widgets_init'
					)
				),
				'admin_menu' => array(
					'methods' => array(
						'admin_menu'
					)
				)
			)
		);
		$args = wp_parse_args($args, $defaults);
		$this->_args = $args;
		$this->_init();	
	}
	protected function _init() {
		$this->_auto_hooks();
		$this->_hooks();
	}
	/*
	 * You can write your hooks here. This method executes just on instance class initialization **/
	abstract protected function _hooks();

	/*
	 * This method just parses the internal methods in this object and automatically add a wordpress hooks with the same names.
	 * FIXME: This method looks like ugly. We can use an array of hooks?
	 * **/
	protected function _auto_hooks() {
		$auto_hooks = @$this->_args['hook_methods'];
		if(is_array($auto_hooks) && !empty($auto_hooks)) {
			foreach($auto_hooks as $hook => $params) {
				$methods = $params['methods'];
				foreach($methods as $method) {
					if(method_exists($this, $method)) {
						if(@$params['type'] == 'filter') {
							add_filter($hook, array($this, $method));
						} else {
							add_action($hook, array($this, $method));
						}
					}
				}
			}
		}	
		
		
		
	}
}
