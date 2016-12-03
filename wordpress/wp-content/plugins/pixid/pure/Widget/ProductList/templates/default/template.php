<div class="resize-column">
<? global $post; foreach($posts as $post): setup_postdata($post); ?>
<div>
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
</div>
<? endforeach; wp_reset_postdata(); ?>
</div>
