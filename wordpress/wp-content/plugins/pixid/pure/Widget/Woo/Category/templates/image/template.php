<?php
/**
 * This template basicly used to display woocommerce category images.
 * From version 0.0.20 We added a image sizes method.
 * TODO: We need to do code like this in some non-caching places as in other frameworks.
 * */
if(empty($category->thumbnail_id)) return "";
if(!$this->args()->get('width') && !$this->args()->get('height')) {
	$image_src = wp_get_attachment_url( $category->thumbnail_id );
} else {
	$image_src = PX()->Media()->get_resized_image_src($category->thumbnail_id, array(
		"width" => $this->args()->get('width'),
		'height' => $this->args()->get('height')
	), $this->args()->get('crop', true));
}
if(!empty($image_src))
	echo '<div class="image"><img style="width: 100%; height: auto" src="' . $image_src . '" alt="" /></div>';
