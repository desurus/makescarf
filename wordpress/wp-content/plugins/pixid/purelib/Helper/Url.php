<?php
namespace PureLib\Helper;
class Url {
	public static function current($replace_args = array()) {
		$removed = remove_query_arg(array_keys($replace_args));
		return add_query_arg($replace_args, $removed);
	}
	public static function ajax($action, $params = array()) {
		if(empty($action) || !is_string($action)) throw new \Exception("Invalid action value! Not empty string required.");
		if(!is_array($params)) throw new \Exception("Params must be a valid array.");
		$params = array_merge(array( 'action' => $action ), $params );
		$url = admin_url('admin-ajax.php');
	        $url = add_query_arg($params, $url);
		return $url;	
	}
}
