<?php
/**
 * A basic template for \Pure\Widget\Woo\Cart widget.
 * TODO: List params here! 
 * */
?>
<div class="cart_price" >
<a href="<? echo $this->args()->get('cart_url', '/cart/'); ?>" >cart<i class="fa fa-shopping-cart"></i><span class="price_c"><? echo $data->get('total_price_num'); ?></span>$</a>
</div>
