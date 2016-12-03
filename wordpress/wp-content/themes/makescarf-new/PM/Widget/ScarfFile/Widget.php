<?php
namespace MakeScarf\Widget\ScarfFile;
class Widget extends \Pure\Widget\Internal {
	public function widget() {
		$post_id = $this->args()->get('post_id');
		$attachment = get_post_meta($post_id, '_scarf_file', true);
		$this->display_template('description.php');
		$this->display_template('generate.php');
		$attachment_url = false;
		$attachment_id = false;
		if(!empty($attachment)) {
			$attachment_id = $attachment;
			$attachment_url = wp_get_attachment_url($attachment_id);
		}
		$this->display_template('file.php', compact('attachment_url', 'attachment_id'));	

	}
}
