<?
wp_enqueue_script('owl-slider', $template->get_template_base_url() . '/owl-carousel/owl.carousel.min.js');

wp_enqueue_style('owl-slider', $template->get_template_base_url() . '/owl-carousel/owl.carousel.css');

wp_enqueue_style('owl-slider-theme', $template->get_template_base_url() . '/owl-carousel/owl.theme.css');

//wp_enqueue_script('owl-slider-bind', $template->get_template_base_url() . '/js/script.js');

//wp_enqueue_style('slider-style', $template->get_template_base_url() . '/css/style.css');
?>
<style>
</style>
<script type="text/javascript">
jQuery(document).ready(function($){
	jQuery('#viewed_products').find('.resize-column').owlCarousel({
		autoPlay: 3000, //Set AutoPlay to 3 seconds
			items : 4,
			autoWidth: false,
			responsiveClass:true,
		navigation : true,
		navigationText: [
			"<i class='icon-chevron-left icon-white'><</i>",
			"<i class='icon-chevron-right icon-white'>></i>"
		]
	});
})
</script>
<div class="">
<? global $post; foreach($posts as $post): setup_postdata($post); ?>
<div class="box">
<div class="item">
	<?php do_action( 'woocommerce_before_shop_loop_item' ); ?>

	

		<?php
			/**
			 * woocommerce_before_shop_loop_item_title hook
			 *
			 * @hooked woocommerce_show_product_loop_sale_flash - 10
			 */
			do_action( 'woocommerce_before_shop_loop_item_title' );
?>
<div class="image-box">
<a href="<? the_permalink(); ?>" title="<? the_title(); ?>"><? the_post_thumbnail('product-thumbnail'); ?></a>
</div>
<?
			/**
			 * woocommerce_shop_loop_item_title hook
			 *
			 * @hooked woocommerce_template_loop_product_title - 10
			 */
?>
<div class="description">
<div><a class="title" href="<? the_permalink(); ?>" title="<? the_title(); ?>"><? the_title(); ?></a></div>
<?
			/**
			 * woocommerce_after_shop_loop_item_title hook
			 *
			 * @hooked woocommerce_template_loop_rating - 5
			 * @hooked woocommerce_template_loop_price - 10
			 */
			do_action( 'woocommerce_after_shop_loop_item_title' );
		?>
</div>
	<?php

		/**
		 * woocommerce_after_shop_loop_item hook
		 *
		 * @hooked woocommerce_template_loop_add_to_cart - 10
		 */
		do_action( 'woocommerce_after_shop_loop_item' );

	?>

</div>
</div>
<? endforeach; wp_reset_postdata(); ?>
</div>
