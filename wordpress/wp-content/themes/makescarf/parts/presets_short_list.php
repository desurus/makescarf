<?
$args = array(
	'post_type' => 'presets',
	'post_status' => 'publish',
	'posts_per_page' => -1,
	'orderby' => 'menu_order',
	'order' => 'ASC'
);
$posts = get_posts($args);
foreach($posts as $post):
$text_color = get_post_meta($post->ID, 'text_color_code', true);
$background_color = get_post_meta($post->ID, 'background_color_code', true);
?>
<? if(!has_post_thumbnail()): ?>
<a title="<? echo $post->post_title; ?>" href="#" class="preset circle" style="width: 75px; height: 75px; display: inline-block; background-color: <? echo $background_color; ?>; color: <? echo $text_color; ?>;" data-background-color="<? echo $background_color; ?>" data-color="<? echo $text_color; ?>">&nbsp;A</a>	
<? else: ?>
<a href="#" class="preset" data-background-color="<? echo $background_color; ?>" data-color="<? echo $text_color; ?>"><? the_post_thumbnail(); ?></a>	
<? endif; ?>
<? endforeach; ?>
