<?php

namespace Pure\Template;

class Finder {
	protected $_widget_classname;
	protected $_template_name;

	protected $_found = null;

	protected $_template_realpath;
	protected $_template_basedir_url;
	protected $_template;	

	protected $_args;
	public function __construct($widget_classname, $template_name = "default", $args = null) {
		$defaults = array(
			'allow_non_existent' => false
		);
		$this->_args = new \PureLib\Config\RawArray($args, $defaults);
		$this->_widget_classname = $widget_classname;
		$this->_template_name = $template_name;
	}

	protected function _get_template_path_part_from_class_name($class_name) {
		if(empty($class_name) || !is_string($class_name)) return false;
		$replaces = array(
			'Pure' => '',
			'\\' => '/'
		);
		$result = str_replace(array_keys($replaces), array_values($replaces), $class_name);
		//Remove trailing "/Widget"
		$result = preg_replace('/\/Widget$/', '', $result);
		return $result;
	}

	public function get_theme_templates_root() {
		return get_template_directory() . '/PM/templates';
	}

	protected function _get_template_search_pathes() {
		$class_name = $this->_widget_classname;	
		$theme_template_path_part = $this->_get_template_path_part_from_class_name($class_name);
		$widget_finder = new \Pure\Widget\Finder($class_name);
		$basedir = $widget_finder->get_directory();
		$search_pathes = array(
			$this->get_theme_templates_root() . trailingslashit($theme_template_path_part),
			$basedir . '/templates'	
		);
		return $search_pathes;
	}
	protected function _locate_template() {
		if(!class_exists($this->_widget_classname, true)) {
			$this->_found = false;
			return false;
		}
		$widget_finder = new \Pure\Widget\Finder($this->_widget_classname);
		$widget_file = $widget_finder->get_file_path();	
		if(empty($widget_file) || !is_file($widget_file))	{
			$this->_found = false;
			return false;
		}

		$basedir = dirname($widget_file);

		$class_name = $this->_widget_classname;	
		//Get a template part name from wigets class to search it in a current template directory hierarchy
		$theme_template_path_part = $this->_get_template_path_part_from_class_name($class_name);	

		$search_pathes = $this->_get_template_search_pathes();	

		$template_name = $this->_template_name;
		if(null == $template_name) {
			$template_name = 'default';	
		}	
		foreach($search_pathes as $path) {
			$full_path = trailingslashit($path) . $template_name;
			if(is_dir($full_path) && file_exists("{$full_path}/template.php")) {	
				$t = self::get_template_instance($class_name, $full_path); 
				$this->_template = $t;
				return $t; 
			} 
		}
		if($this->_args->get('allow_non_existent')) {
			$root = $this->get_theme_templates_root() . trailingslashit($theme_template_path_part);
			$full_path = $root . $template_name;
			$t = self::get_template_instance($class_name, $full_path);
			$this->_template = $t;
			return $this->_template;
		}
		return false;
		//throw new \Pure\Template\Exception("Can not find template named '{$template_name}' for widget {$class_name}.");	
	}
	/**
	 * This method does not doing a real template creation, it's just generate a full path to the new template in current wordpress theme
	 * */
	public function create_template() {
		$class_name = $this->_widget_classname;
		$template_name = $this->_template_name;
		
		$theme_template_path_part = $this->_get_template_path_part_from_class_name($class_name);
		$root = $this->get_theme_templates_root() . trailingslashit($theme_template_path_part);
		
		$full_path = $root . $template_name;
		$t = new Template($class_name, $full_path);
		$this->_template = $t;
		return $this->_template;
	}
	public function get_template() {
		//$allow_non_existent = $this->args()->get('allow_non_existent', true);//???
		$allow_non_existent = true;
		if($this->_found === null) {
			return $this->_locate_template($allow_non_existent);
		}	
		if($this->_found === false) {
			//TODO
		}
		return $this->_template;
	}
	/**
	 * This method returns a full array with all available templates for some widget */
	public function find_all() {
		$search_pathes = $this->_get_template_search_pathes();
		$templates = array();
		foreach($search_pathes as $path) {
			$directory = new \PureLib\FS\Node\Directory($path);
			if(!$directory->exists()) continue;
			$children = $directory->get_child_nodes();
			if(empty($children)) continue;
			foreach($children as $node) {
				if(($node instanceof \PureLib\FS\Node\Directory)) {
					try {
						$template = self::get_template_instance($this->_widget_classname, $node->get_fullpath());
						$templates[] = $template;
					} catch(\Exception $e) {
					
					}
				}
			}	
		}			
		return $templates;
	}
	/**
	 * This method builds a new Template instance
	 * @param string $class_name Just a widget classname. It's required by a Template class.
	 * @param string $full_path A full path to a template directory.
	 * */
	public static function get_template_instance($class_name, $full_path) {
			
		if(file_exists(trailingslashit($full_path) . '/Template.php')) {
			
		}
		return new Template($class_name, $full_path);
	}
}
