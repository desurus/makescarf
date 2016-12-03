<?
/* Template Name: Empty page */
?>
<? get_header(); ?>
<div class="container default">
<? while(have_posts()): the_post(); ?>

							   	<? PM()->display_widget('\Pure\Widget\Post\Content'); ?> 
 
<? endwhile; ?>
</div>
<? get_footer(); ?>	
