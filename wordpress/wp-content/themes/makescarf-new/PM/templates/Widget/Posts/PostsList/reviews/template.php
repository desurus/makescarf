<script type="text/javascript">
jQuery(document).ready(function(){
	jQuery("#owl-demo").owlCarousel({

		autoPlay: 3000, //Set AutoPlay to 3 seconds

			items : 1,
			itemsDesktop : [1199,1],
			itemsDesktopSmall : [979,1],
			itemsTablet : [768,1],
			navigation : true,
			navigationText: ["<img src='<? echo get_template_directory_uri() ?>/images/main/reviews/ar_l.png' alt='Prev' />","<img src='<? echo get_template_directory_uri(); ?>/images/main/reviews/ar_r.png' alt='Next' />"]
	});
})
	</script>
<h3 class="title_reviews" >DON'T JUST TAKE IT FROM US...</h3>
<div id="owl-demo" class="owl-carousel owl-theme">
							
<? global $post; foreach($posts as $post): setup_postdata($post); ?>
<div class="item">
	<div class="ever_review" >
		<div class="img_user_name" >
<? the_post_thumbnail(array(150, 150)); ?>			
<p class="name_user_rev" ><? echo get_post_meta($post->ID, 'author_name', true); ?></p>
	<p class="who_user" ><? echo get_post_meta($post->ID, 'who_is_author', true); ?></p>
									</div>
									<div class="rev_user_about" >
									    
<? the_content(''); ?>		
									</div>
								</div>
							</div>
<? endforeach; wp_reset_postdata(); ?>
						</div>
