<?php
$slides = $slider->get_slides()->posts;
?>
	<ul class="<? echo $this->args()->get('list_class', 'slides'); ?>">
<? foreach($slides as $slide): ?>								
<li>
									<img src="<? echo PM()->Media()->get_resized_image_src($slide->ID, array(750, 600)); ?>" alt="">
								</li>
<? endforeach; ?>
	
							</ul>
