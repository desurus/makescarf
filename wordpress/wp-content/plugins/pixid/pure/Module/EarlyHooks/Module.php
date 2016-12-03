<?php
/**
 * This is a temporary module to fix an issues with widgets that need to install a very early wordpress hooks. Such as a temporary widget \Pure\Widget\CuteFilter
 * */
namespace Pure\Module\EarlyHooks;

class Module extends \Pure\Module {	
	public function _init() {
		add_action('init', array($this, 'wp_init'), 9999);
	}
	public function wp_init() {
		//FIX woocommerce price filter enable!
		if(\Pure\Helper\Woocommerce::enabled())
			add_filter( 'loop_shop_post_in', array( WC()->query, 'price_filter' ) );
	}
}
