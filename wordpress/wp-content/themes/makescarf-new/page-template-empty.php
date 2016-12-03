<?
/* Template Name: Woocommerce empty page */
?>
<? get_header(); ?>
<div style="padding: 15px;" class="woocommerce">
<? while(have_posts()): the_post(); ?>

							   	<? PM()->display_widget('\Pure\Widget\Post\Content'); ?> 
 
<? endwhile; ?>
</div>
<? get_footer(); ?>	
