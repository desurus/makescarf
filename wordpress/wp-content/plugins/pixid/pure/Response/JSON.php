<?php

namespace Pure\Response;
class JSON {
	public $code;
	public $message;
	public $data;

	const CODE_SUCCESS 	= 0;
	const CODE_ERROR 	= 1;

	public function __construct($code = 0, $message = "", $data = array()) {
		$this->set_code($code)
			->set_message($message)
			->set_data($data);
	}

	public function set_code($code) {
		$this->code = $code;
		return $this;
	}
	public function set_message($message) {
		$this->message = $message;
		return $this;
	}
	public function set_data($data) {
		$this->data = $data;
		return $this;
	}

	public function to_string() {
		return json_encode($this);
	}
	public function __toString() {
		return $this->to_string();
	}

	public function send($exit = true) {
		header("Content-type: application/json");
		echo $this;
		if($exit) exit();
	}
}
