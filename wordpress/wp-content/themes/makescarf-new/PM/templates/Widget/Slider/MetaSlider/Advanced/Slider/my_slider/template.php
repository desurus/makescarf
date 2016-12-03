<?
$posts = $slider->get_slides()->posts;
?>
<div class="container scarves_ideas" ><!-- scarves_ideas -->
		    <div class="row" >
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 " >
				    <h2 class="title_ideas" >Scarves IDEAS</h2>
				</div>
			</div>	
		    <div class="row lines_ideas" >
<? 
global $post;

foreach($posts as $post): setup_postdata($post); 

if(!has_post_thumbnail()) continue;
$thumbnail_id = get_post_thumbnail_id();
$src = wp_get_attachment_image_src($thumbnail_id, 'full');
$src = $src[0];
?>				
<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 " >
				    <div class="bl_ideas_any" >
				    <a class="ever_link_ideas" rel="gallery" href="<? echo $src; ?>" class="fancybox" title="<? the_title(); ?>">
						<? 
//Original in src 360x235
the_post_thumbnail('full', array('class' => 'img-responsive')); ?>
					    <p class="name_any_ideas" ><? the_title(); ?></p>
						</a>
					</div>
				</div>
<? endforeach; wp_reset_postdata(); ?>	
			</div>	
		</div><!--/# scarves_ideas -->
