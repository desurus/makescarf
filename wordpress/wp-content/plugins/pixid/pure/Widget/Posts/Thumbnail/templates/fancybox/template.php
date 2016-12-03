<?
	$preview = wp_get_attachment_image_src($post->ID, array($this->args()->get('thumbnail_width'), $this->args()->get('thumbnail_height')));
	$preview_src = $preview[0];
?>
	<a href="<? echo $post->guid; ?>" class="fancybox" ><img alt="" src="<? echo $preview_src; ?>" class="img-responsive"  /></a>
