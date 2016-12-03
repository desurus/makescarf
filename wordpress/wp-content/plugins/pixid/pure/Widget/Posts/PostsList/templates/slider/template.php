<?php
if(empty($posts)) return;
wp_enqueue_script('owl-slider', $template->get_template_base_url() . '/owl-carousel/owl.carousel.min.js');

wp_enqueue_style('owl-slider', $template->get_template_base_url() . '/owl-carousel/owl.carousel.css');

wp_enqueue_style('owl-slider-theme', $template->get_template_base_url() . '/owl-carousel/owl.theme.css');

wp_enqueue_script('owl-slider-bind', $template->get_template_base_url() . '/js/script.js');

wp_enqueue_style('slider-style', $template->get_template_base_url() . '/css/style.css');
?>
<div class="owl-carousel" id="carousel_default">	
					<!-- slides-->
<? $i = 0; foreach($posts as $post): $i++; ?>						
<div class="item">
<? 
//Widget in widget xD 
PM()->display_widget('\Pure\Widget\Posts\Thumbnail\Widget', [ 'post' => $post, 'class' => 'img-responsive', 'alt' => $post->post_title ]);
?>
</div>
<? endforeach; ?>
</div>
