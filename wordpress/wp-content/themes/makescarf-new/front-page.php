<? get_header(); ?>
	    <div class="container" >
			<div class="row " >
				<? PM()->display_widget('\Pure\Widget\Slider\MetaSlider\Advanced\Slider', array(
					'slider_id' => 1150,
					'template' => 'default'	
				)); ?>	
			</div>
			<div class="row" ><!-- get started -->
			    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" >
				    <div class="create_bl" >
					   	<? PM()->display_snippet('small_get_started_block.php'); ?> 
					</div>
				</div>
			</div><!--/# get started  -->

<? PM()->display_snippet('how_it_works_block.php'); ?>	
		</div>	
<? PM()->display_snippet('video_on_main_block.php'); ?>
<? PM()->display_snippet('samples_block.php'); ?>	

		<div class="reviews_users" ><!-- reviews_users -->
		    <div class="container" >
				<div class="row" >
					<div class="col-lg-1 col-md-1 col-sm-1 col-xs-12" ></div>
					<div class="col-lg-10 col-md-10 col-sm-10 col-xs-12" >
<? PM()->display_widget('\Pure\Widget\Posts\PostsList\Widget', array(
	'post_type' => 'reviews',
	'post_status' => 'publish',
	'posts_per_page' => -1,
	'template' => 'reviews'
)); ?>					
					</div>
					<div class="col-lg-1 col-md-1 col-sm-1 col-xs-12" ></div>
				</div>
			</div>	
		</div><!--/# reviews_users -->	

<? PM()->display_snippet('big_library_block.php'); ?>		

<? PM()->display_snippet('questions_block.php'); ?>	
<? get_footer(); ?>	
