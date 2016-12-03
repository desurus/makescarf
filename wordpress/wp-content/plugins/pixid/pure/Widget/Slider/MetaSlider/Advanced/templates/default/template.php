<?php
$slides = $slider->get_slides()->posts;
?>
<div data-ride="carousel" class="carousel slide top-slider" id="carousel">	
<!-- slides-->
<div class="carousel-inner">
<? $i = 0; foreach($slides as $slide): $i++; ?>						
<div class="item ever_sl <? if($i == 1) echo "active"; ?>">
<? 
PM()->display_widget('\Pure\Widget\Posts\Thumbnail\Widget', array( 
	'post' => $slide, 
	'class' => 'img-responsive', 
	'alt' => $slide->post_title, 
	'template' => 'default' 
));
?>
<? /* <h3 class="text_ever_slider" ><? ?></h3> */?>
</div>
<? endforeach; ?>
					</div>
					<!-- Strelki pereklucheniya slaydov-->
					<a data-slide="prev" class="left carousel-control" href="#carousel">
						<img alt="Previous" src="<? echo get_template_directory_uri(); ?>/images/main/slider/arrow_left.png" />
					</a>
					<a data-slide="next" class="right carousel-control" href="#carousel">
						<img alt="Next" src="<? echo get_template_directory_uri(); ?>/images/main/slider/arrow_right.png"  />
					</a>
				</div>
