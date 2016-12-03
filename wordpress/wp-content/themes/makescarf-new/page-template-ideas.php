<? 
/* Template Name: Scarves Ideas */
?>
<? get_header(); ?>
<? while(have_posts()): the_post(); ?>


<? PM()->display_widget('\Pure\Widget\Posts\PostsList\Widget', array(
	
	'post_type' => 'scarf_gallery',
	'posts_per_page' => -1,
	'template' => 'scarves_ideas',
	
	'append_edit_buttons' => array(
		array(
			'icon' => 'edit',
			'title' => 'Edit gallery',
			'link' => admin_url('edit.php?post_type=scarf_gallery')
		)
	)

)); ?> 



<? endwhile; ?>
<? get_footer(); ?>	
