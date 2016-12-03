<?php

namespace Pure\Editor;

class Button {
	protected $_args;
	public function __construct($button) {
		$this->_args = new \PureLib\Config\RawArray($button);
	}
	public function args() {
		return $this->_args;
	}
	public function render() {
		$link = $this->args()->get('link', 'javascript://');
		$icon = $this->args()->get('icon', 'null');
		$title = $this->args()->get('title', '');
		$action = $this->args()->get('action_url', '');
		if($this->args()->get('modal')) $action = $link;
		$classes = array(
			'pm-icon',
			'pm-button',
			'pm-icon-'.$icon,
			'dashicons-before',
			'dashicons-'.$icon	
		);
		$data_action_url = "";
		$data_string = "";
		if(!empty($action)) {
			$classes[] = 'pm-action-trigger';
			$data_action_url = $action;
			$data_string .= " data-action-url=\"{$data_action_url}\"";
		}
		if($this->args()->get('dialog_width')) {
			$width = $this->args()->get('dialog_width');
			$data_string .= " data-dialog_width=\"{$width}\"";
		}
		if($this->args()->get('dialog_height')) {
			$height = $this->args()->get('dialog_height');
			$data_string .= " data-dialog_height=\"{$height}\"";
		}
		$classes = implode(' ', $classes);

		$html = "<a href=\"{$link}\" class=\"{$classes}\" title=\"{$title}\"{$data_string}></a>";
		echo $html;	
	}
}
