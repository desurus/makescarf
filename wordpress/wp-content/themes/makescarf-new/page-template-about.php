<?
/* Template Name: Template for About page. */
?>
<? get_header(); ?>
<? while(have_posts()): the_post(); ?>
<div class="library_first" ><!-- library_second -->
		    <div class="container" >
			    <div class="row" >
			    <h2 class="title_libr"><? PM()->display_widget('\Pure\Widget\Post\Title'); ?></h2>
					<hr class="other_lines">
				</div>
				
			</div>
            <div class="about_back_bl"><!-- about_back_bl -->
				<div class="container ">
					<div class="row">
						<div class="col-lg-7 col-md-7 col-sm-7 col-xs-12">
							
						</div>
						<div class="col-lg-5 col-md-5 col-sm-5 col-xs-12 resset_pad">
							<div class="info_ab_us" >
							   	<? PM()->display_widget('\Pure\Widget\Post\Content'); ?> 
							</div>
						</div>
					</div>
				</div>	
		    </div>			
		</div><!--/#about_back_bl --> 
<? endwhile; ?>
<? get_footer(); ?>	
