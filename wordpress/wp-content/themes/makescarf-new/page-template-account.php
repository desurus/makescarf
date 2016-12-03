<?
/* Template Name: Account */
?>
<? get_header(); ?>
<div class="container default">
<? while(have_posts()): the_post(); ?>
<div class="row">

				<div class="cap-wrap">
				<h2 class="title_ideas">My Account</h2>
<? PM()->display_widget('Pure\Widget\Menu\Widget', array(
	'theme_location' => 'account_menu',
	'template' => 'list',
	'list_class' => 'cap-tabs',
	'active_class' => 'tab-active'
)); ?>					
				</div>

			</div>
<div class="row">
<div class="co">

<? PM()->display_widget('\Pure\Widget\Post\Content'); ?> 
 
</div>
</div>
<? endwhile; ?>
</div>
<? get_footer(); ?>	
