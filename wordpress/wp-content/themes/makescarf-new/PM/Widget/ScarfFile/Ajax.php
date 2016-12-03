<?php

namespace MakeScarf\Widget\ScarfFile;
class Ajax extends \Pure\Module\Ajax\Handler {
	public function create_file_action() {
		$post = $this
				->widget()
				->args()
				->get('post_id');
		$url = get_permalink($post);
		$url = add_query_arg('full_preview', 'true', $url);
		$phantom_script_path = $this
						->widget()
						->get_widget_directory() . '/phantom/print.js';
		$upload_dir = wp_upload_dir();
		$output_file = $upload_dir['path'] . '/' . $post . '.pdf';
		$output_file_url = $upload_dir['url'] . '/' . $post . '.pdf';
		//TODO: If file exists...	
		//TODO: Add sizes support to print script.
		$string_result = "";
		$exec = "phantomjs --ignore-ssl-errors=true {$phantom_script_path} {$url} {$output_file}";
		$execute_result = trim(shell_exec($exec));
		$attachment_id = false;
		$html = '';
		if($execute_result == "Complete!") {
			$string_result = "File created!";
			$attachment_id = \PureLib\Utils::append_file_to_post($output_file, $post);
			update_post_meta($post, '_scarf_file', $attachment_id);
			$attachment_url = wp_get_attachment_url($attachment_id);
			$html = $this
				->widget()
				->fetch_template('file.php', compact('attachment_id', 'attachment_url'));
		} else {
			$string_result = "File not created. Advanced info provided in response data object.";
		}
		$response = new \Pure\Response\JSON(\Pure\Response\JSON::CODE_SUCCESS, $string_result, array(
			"execute" => $exec,
			"execute_result" => $execute_result,
			"output_file" => $output_file,
			"output_file_url" => $output_file_url,
			"execute_result" => $execute_result,
			'attachment_id' => $attachment_id,
			'html' => $html
		));
		echo $response;
		exit();
	}
}
