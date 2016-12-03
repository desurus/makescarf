<?php

namespace Pure\Widget\Woo\Cart;
class Widget extends \Pure\Widget {
	public function widget() {
		$this->include_css('woocommerce');
		$this->include_js('woocommerce');		
		$total_price_num = PM()->Woo()
					->get_cart()
					->total;	
		$this->display_template(compact('total_price_num'));
	}
}
