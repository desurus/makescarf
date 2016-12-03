<?php
namespace Pure\Helper\View;
class Widget extends \PureLib\View\Helper {
	public function display_name($widget) {
		$finder = null;
		if(is_object($widget) && ($widget instanceof \Pure\Widget)) {
			$finder = $widget->finder();
		} else {
			try {
				$finder = new \Pure\Widget\Finder($widget);
			} catch(\Pure\Exception $e) {
				$finder = null;
			}
		}
		if(!is_object($finder)) {
			//Error here
			return;
		}
		$widget_classname = $finder->get_widget_classname();
		$this->render("widget_name_line.php", array(
			'widget_classname' => $widget_classname,
			'finder' => $finder
		));	
	}
}
