<? 
/* Template Name: Wholesalers page */
?>
<? get_header(); ?>
<? while(have_posts()): the_post(); ?>
<div class="container" >
			<div class="row " >
<? PM()->display_widget('\Pure\Widget\Slider\MetaSlider\Advanced\Slider', array(
					'slider_id' => 1155,
					'template' => 'default'	
)); ?>	
			</div>
		</div>	
<? PM()->display_snippet('wholesalers/popup_form.php'); ?>	
<? PM()->display_snippet("quote_large_block.php"); ?>	
<? PM()->display_snippet("wholesalers/video.php"); ?>	
<? PM()->display_snippet('wholesalers/some_items.php'); ?>	
		
		<div class="gallery_bl" >
		    <div class="container" >
			    <div class="row" >
				    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" >
					    <h4 class="name_gallery" >gallery</h4>
					</div>
				</div>
				
				<div class="row" >
<? PM()->display_widget('\Pure\Widget\Slider\MetaSlider\Advanced\Slider', array(
					'slider_id' => 1165,
					'template' => 'owl_slider'	
)); ?>
					
				</div>
            </div>				
		</div>
<? endwhile; ?>
<? get_footer(); ?>	
