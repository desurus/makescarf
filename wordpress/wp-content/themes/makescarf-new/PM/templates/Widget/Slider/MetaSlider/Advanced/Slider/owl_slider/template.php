<script>
jQuery(document).ready(function(){
	jQuery("#owl-demo-2").owlCarousel({

          autoPlay: 3000, //Set AutoPlay to 3 seconds

          items : 5,
          itemsDesktop : [1199,3],
          itemsDesktopSmall : [979,3],
          navigation : true,
	  navigationText: ["<img src='<? echo get_template_directory_uri(); ?>/images/main/reviews/ar_l.png' alt='Prev' />","<img src='<? echo get_template_directory_uri(); ?>/images/main/reviews/ar_r.png' alt='Next' />"]
        });
})
</script>
<div id="owl-demo-2" class="owl-carousel owl-theme">
<? $slides = $slider->get_slides()->posts; ?>
<? 
global $post; foreach($slides as $post): setup_postdata($post); 
?>					    
<div class="item">
	<a class="fancybox image" href="<? echo PX()->Media()->get_image_src($post->ID); ?>">
<img src="<? echo PX()->Media()->get_resized_image_src($post->ID, array(225, 160)); ?>" alt="<? echo $post->post_title; ?>">
</a>
</div>
<? endforeach; wp_reset_postdata(); ?>			    
					</div>
