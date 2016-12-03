<? query_posts(array('post_type' => 'slides', 'post_status' => 'publish')); ?>
<? if(have_posts()): ?>
<section class="slider">
					<div class="flexslider">
						<ul class="slides">
<? while(have_posts()): the_post(); ?>							
<li><? the_post_thumbnail('full'); ?>
<a href="<? echo get_permalink(MakeScarf_Theme::Me()->options->GetOption('makescarf_page', 31)); ?>" class="btn">Get Started</a>
								<span class="wrapperSlides"></span>
							</li>
<? endwhile; ?>
					</ul>
					</div>
				</section>
<? endif; wp_reset_query(); ?>
