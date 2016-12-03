<?php
/**
 * $data contains next variables:
 * scarf: The WC_Product_Simple object which contains all required info about this saved scarf. This is a simple woocommerce product, which available in the Product section in admin panel.
 * */
$scarf = $data->get('scarf');
?>
	<div class="scarf_cart_preview" style="background-color: #<? echo $scarf->get_attribute('background_color'); ?>; font-family: <? echo $scarf->get_attribute('font'); ?>; color: #<? echo $scarf->get_attribute('font_color'); ?>" 
data-scarf_id="<? echo $scarf->id; ?>">
<? echo strip_tags($scarf->post->post_content); ?>
</div>
