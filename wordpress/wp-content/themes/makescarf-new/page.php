<? get_header(); ?>
<div class="container default" >
<? while(have_posts()): the_post(); ?>
		    <div class="row" >
				
				<h2 class="title_ideas" ><? PM()->display_widget('\Pure\Widget\Post\Title'); ?></h2>
				
			</div>	
		    <div class="row" >
				<div class="co">
<? the_content(); ?>
</div>		
			</div>	
<? endwhile; ?>
		</div>
<? get_footer(); ?>	
