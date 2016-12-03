<? 
//Seems instagram block persist at every page
if(PM()->request()->get('iframe') != 'true')
	PM()->display_snippet('instagram_block.php'); ?> 
</div><!--/# main_content -->
<? if(PM()->request()->get('iframe', 'false') != 'true'): ?>
	<footer>
	    <div class="container" >
			    <div class="row" >
				    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" >
<? PM()->display_widget('\Pure\Widget\Menu\Widget', array( 'theme_location' => 'main_menu', 'menu_class' => 'footer_menu', 'container' => '' )); ?> 
					</div>
				</div>
				<div class="row foot_social" >
				    <div class="col-lg-5 col-md-5 col-sm-5 col-xs-12" >
					   	<? PM()->display_snippet('footer_logo.php'); ?> 
					</div>
				    <div class="col-lg-7 col-md-7 col-sm-7 col-xs-12" >
					    <div class="group_soc_foot">
							<? PM()->display_snippet('social_links_block.php'); ?>	
						</div>
					</div>
				</div>	
				<div class="row" >
				<? PM()->display_snippet('copyright.php'); ?>
				</div>
	    </div>				
	</footer> 
<? wp_footer(); ?>
<? endif; ?>
    </body>
</html>
