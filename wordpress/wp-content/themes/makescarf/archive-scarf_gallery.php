<?
get_header();
?>
<style>
.scarf-gallery li {
	float: left;
	margin: 10px;
	height: 200px;
}
</style>
<ul class="scarf-gallery"> 
<? while(have_posts()): the_post(); if(!has_post_thumbnail()) continue; ?>
<li>
<? $attachment = wp_get_attachment_url(get_post_thumbnail_id());?>
<a href="<? echo $attachment; ?>" class="fancybox" rel="gal"><? the_post_thumbnail(array(240, 180)); ?></a>
</li>
<? endwhile; ?>
</ul>
<? get_footer(); ?>
