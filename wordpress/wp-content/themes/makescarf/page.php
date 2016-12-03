<?php
get_header(); ?>
		<?php if(have_posts()): the_post(); ?>
<div id="main-content" class="page page-<?the_ID(); ?>">
	<h1><? the_title(); ?></h1>
<? the_content(); ?>
</div>
<? endif; ?>


<?php get_footer(); ?>
