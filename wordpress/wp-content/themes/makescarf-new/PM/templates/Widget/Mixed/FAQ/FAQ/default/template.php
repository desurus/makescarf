<div class="lines_help">
<? global $post; foreach($posts as $post): setup_postdata($post); ?>
<div class="ever_bl_help_nechet">
	<p class="quest_help"><span class="icon_quest">q:</span> <? the_title(); ?></p>
	<p class="answ_help"><span class="icon_help">a:</span> </p><? the_content(); ?><p></p>
</div>
<? endforeach; wp_reset_postdata(); ?>
</div>
