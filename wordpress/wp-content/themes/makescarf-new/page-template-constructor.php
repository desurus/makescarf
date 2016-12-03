<?
/* Template Name: Constructor */
?>
<? get_header(); ?>

<? while(have_posts()): the_post(); ?>

							   	<? PM()->display_widget('\Pure\Widget\Post\Content'); ?> 
 
<? endwhile; ?>

<? get_footer(); ?>	
