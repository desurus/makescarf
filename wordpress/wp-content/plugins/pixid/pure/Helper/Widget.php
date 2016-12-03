<?php

namespace Pure\Helper;

class Widget {
	/**
	 * This method checks if provided widget classname is existing and registered widget.
	 * @param string $widget Widget classname
	 * @return boolean
	 * */
	public static function is_valid($widget) {
		return class_exists($widget, true);
	}
	/*
	 * This method just compare a names of widget classes, not any other compare stuff here!
	 * @param string $widget_first
	 * @param string $widget_second
	 * @return boolean 
	 * **/
	public static function is_same_widget_class($widget_first, $widget_second) {
		$class_name_fw = $widget_first;
		if(is_object($widget_first)) $class_name_fw = get_class($widget_first);
		if(!is_string($class_name_fw) || empty($class_name_fw)) return false;

		$class_name_sw = $widget_second;
		if(is_object($widget_second)) $class_name_sw = get_class($widget_second);
		if(!is_string($class_name_sw) || empty($class_name_sw)) return false;

		$class_name_fw = ltrim($class_name_fw, '\\');
		$class_name_sw = ltrim($class_name_sw, '\\');
		return ($class_name_fw == $class_name_sw);
	}

	/**
	 * This method gets a widget settings container based on data from \Pure\Widget class instance.
	 * @param \Pure\Widget\Call $widget_call
	 * @return \Pure\Settings\Container
	 * */
	public static function get_widget_settings_container(\Pure\Widget\Call $widget_call)	{
		$finder = new \Pure\Widget\Finder($widget_call->get_widget_name());
		if(file_exists($finder->get_directory() . "/Settings.php")) {
			$widget_namespace = $finder->get_namespace();
			$settings_class_name = "{$widget_namespace}\Settings";
			$container = new $settings_class_name($widget_call->get_widget_name());	
		} else {
			//TODO: Initialize some default container?
			$container = new \Pure\Settings\Container\WithTemplate($widget_call->get_widget_name());	
		}
		$args = $widget_call->get_widget_call_args();	
		$container->set_values($args);
		return $container;
	}
	/**
	 * This method try to save a new widget call settings base on initialized \Pure\Widget\Call class and a new array of settings.
	 * Seems this is a something like a temporary wrapper... ?
	 * @param \Pure\Widget\Call
	 * @param mixed $settings
	 * @return boolean
	 * @throws \Pure\Exception
	 * */
	public static function save_call_settings(\Pure\Widget\Call $widget_call, $settings) {
		if(!is_array($settings) || empty($settings)) throw new \Pure\Exception("The settings object can not be empty.");
		//Here we need to merge the old args with a new ones, and override
		$settings = array_merge($widget_call->get_widget_call_args(), $settings);

		$direct_call = $widget_call->get_direct_widget_call();
		$call = new \Pure\Widget\Call();
		$new_direct_call = $call
			->set_widget_name($widget_call->get_widget_name())
			->set_widget_call_args($settings)
			->build_direct_widget_call()
			->get_direct_widget_call();
		if(empty($direct_call)) {
			throw new \Pure\Exception(__("Could not find the source widget call code", WAMT_DOMAIN));
		}	
		if(empty($new_direct_call)) {
			throw new \Pure\Exception(__("Seems we could not build a source code for new widget call, please, report this incident!", WAMT_DOMAIN));
		}
		$source_file = new \PureLib\FS\Node\File($widget_call->get_widget_call_file());
		if(!$source_file->is_writable()) {
			throw new \Pure\Exception(sprintf(__("The target file ``%s`` is not writable. Can not save widget call", WAMT_DOMAIN), $source_file->get_fullpath()));
		}
		$source_file_content = $source_file->get_file_content();
		if(false === strpos($source_file_content, $direct_call)) {
			throw new \Pure\Exception(sprintf(__("Can not find the source widget call in source file ``%s``", WAMT_DOMAIN), $source_file->get_fullpath()));
		}
		$source_file_content = str_replace($direct_call, $new_direct_call, $source_file_content);
		PM()->module("Backup")->maybe_backup_file($source_file);
		$source_file->put_file_content($source_file_content);
		return true;
	}
}
