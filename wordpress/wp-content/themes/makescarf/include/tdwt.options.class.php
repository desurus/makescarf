<?php
/**
 * @version 0.1
 * @author Rei
 * This class help you to easy create a settings pages and manipulate with options.
 * This class is under development.
 * TODO: Need to rewrite the TDWT_Options::SettingsPage function with using a classes of input elements. I think this is more better.
 */
class TDWT_Options {
	public $options;
	public $menu_title;
	public $page_title;
	public $capability;
	public $menu_slug;
	public function __construct($options) {
		$this->options = $options;		
	}
	public function CreateThemeOptionsPage($menu_title, $page_title, $menu_slug, $capability = 'manage_options') {
		$this->menu_title = $menu_title;
		$this->page_title = $page_title;
		$this->capability = $capability;
		$this->menu_slug = $menu_slug;
		add_action('admin_menu', array($this, 'RegisterThemeSettingsPage'));
	}
	public function RegisterThemeSettingsPage() {
		add_theme_page($this->page_title, $this->menu_title, $this->capability, $this->menu_slug, array($this, 'SettingsPage'));
	}
	public function LoadOptions($reload = false) {
		static $_options;
		if(!is_array($_options) || true == $reload) {
			foreach($this->options as $option => $params) {
				$_options[$option] = maybe_unserialize(get_option($option, $params['default']));
				$this->options[$option]['value'] = $_options[$option];
			}
		}
		return $_options;
	}
	public function GetOption($option, $default = null) {
		$this->LoadOptions();
		if(!isset($this->options[$option]))
			return $default;
		return $this->options[$option]['value'];
	}
	public function SettingsPage() {
		if('POST' == $_SERVER['REQUEST_METHOD']) {
			foreach($_REQUEST['options'] as $opt => $new_value) {
				$new_value = stripslashes($new_value);
				update_option($opt, maybe_serialize($new_value));
				//TODO: working with checkboxes...
			}
		}
		$this->LoadOptions();
?>
<style>
.form-wrap textarea {
	width: 500px;
	height: 150px;
}
</style>
<div class="wrap">
<h2><? echo $this->page_title; ?></h2>
<? if(!empty($this->options)): ?>
<div class="form-wrap">
<form action="" method="post">
<? foreach($this->options as $option_name => $params): ?>
<label style="font-weight: bold; cursor: pointer;" for="<?=$options_name; ?>"><? echo $params['title']; ?></label>
<? if(!empty($params['description'])): ?>
<p style="font-style: italic;"><? echo $params['description']; ?></p>
<? endif; ?>
<?
		switch($params['type']): 
		case 'textarea':
			?>
				<textarea name="options[<? echo $option_name?>]" id="<? echo $option_name; ?>"><? echo $params['value']; ?></textarea>
<?
			break;
		case 'text':
			?>
				<input type="text" name="options[<? echo $option_name; ?>]" value="<? echo $params['value']; ?>" />
<?
			break;
		case 'number':
			?>
				<input type="number" name="options[<? echo $option_name; ?>]" value="<? echo $params['value']; ?>" />
<?
			break;
		default:
			echo 'This option type not supported yet.';
			break;
		endswitch;
?>
<? endforeach; ?>
<p>
<input type="submit" name="save" value="Save" class="button button-primary button-large" />
</p>
</form>
</div>
<? endif; ?>
</div>
<?	
	}
}
?>
