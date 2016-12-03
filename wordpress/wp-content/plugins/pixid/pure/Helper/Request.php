<?php

namespace Pure\Helper;
class Request {
	public function is_post_request() {
		return ($_SERVER['REQUEST_METHOD'] == 'POST');
	}
}
