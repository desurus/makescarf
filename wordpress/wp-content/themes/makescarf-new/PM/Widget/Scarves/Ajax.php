<?php

namespace MakeScarf\Widget\Scarves;
class Ajax extends \Pure\Module\Ajax\Handler {
	public function delete_scarf_action() {
		$scarf_id = intval($this->request()->get('scarf_id', 0));
		if(!PM()->User()->is_logged_in()) {
			$response = array('status' => 1, "message" => "You are not logged in.");
			echo json_encode($response);
			exit();
		}
		$user = PM()->User()
				->get_current_user();

		if(!empty($scarf_id)) {
			$product = PM()->Woo()
					->get_product_by_id($scarf_id);
			if(!$product) {
				$response = array('status' => 1, 'message' => 'Product not found.');
				echo json_encode($response);
				exit();
			}
			if($product->post->post_author != $user->ID) {
				$response = array('status' => 1, 'message' => 'You can not delete this scarf. Hacker? Uhm.');
				echo json_encode($response);
				exit();
			}
			//$result = \WC_API_Products::delete_product($scarf_id, true);
			$result = true;
			if($result) {
				$parent_id = wp_get_post_parent_id( $scarf_id );
				wp_delete_post($scarf_id, true);
				if($parent_id) {
					wc_delete_product_transients($parent_id);	
				}
				$response = array('status' => 0, 'message' => 'Scarf deleted!');
				echo json_encode($response);
				exit();
			}
			$response = array('status' => 1, 'message' => 'Something goes wrong. Can not delete scarf.');
			echo json_encode($response);
			exit();
		}
	}
}
