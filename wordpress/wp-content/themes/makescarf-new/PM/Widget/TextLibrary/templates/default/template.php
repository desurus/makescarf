<?php 
$this->include_js('perfect-scrollbar', get_template_directory_uri() . '/js/perfect-scrollbar/perfect-scrollbar.min.js');
$this->include_css('perfect-scrollbar', get_template_directory_uri() . '/js/perfect-scrollbar/css/perfect-scrollbar.min.css');
$this->include_js('library-js', $this->get_template_directory_uri() . '/js/script.js');
$this->include_css('library-css', $this->get_template_directory_uri() . '/css/style.css');
?>
<script type="text/javascript">
var library_data = <? echo $encoded_data; ?>;
jQuery(document).ready(function($){
	$('.table-responsive').hide();
	$('.list_ever_lib > li > a').click(function(){
		var term_id = $(this).data('term_id');
		$('.table-responsive').hide();
		$('#table-posts-'+term_id).show();
		Ps.update(document.getElementById('library_text'));	
		return false;
	});
	$('.list_ever_lib > li:first-child > a').trigger('click');
	
	$('.add_post_to_constructor').click(function(){
		var post_id = $(this).data('post_id');
		if($(this).hasClass('added')) {
			$(this).removeClass('added');
			$(this).text("Add");
			$('#posts_list_form').find('input[value="'+post_id+'"]').remove();
			return false;
		}	
		var input = $('<input type="hidden" name="selected_posts[]" value="'+post_id+'" />');
		$(input).appendTo('#posts_list_form');
		$(this).addClass('added');
		$(this).text("Remove");
		return false;
	});

	$('#submit_posts_form').click(function(){
		$('#posts_list_form').trigger('submit');
		return false;
	})

});
</script>
<div class="inner_bl_fir_lib" >
				    <div class="col-lg-3 col-md-3 col-sm-4 col-xs-12 resset_pad" >
					    <div class="left_cat" >
						    <h3 class="title_ever_lib" >1.our text library</h3>
						    <ul class="list_ever_lib scrollbar" >
							   <? foreach($data->get('data') as $term): ?>
<? if(empty($term['items'])) continue; ?>
<li><a href="#term-<? echo $term['id']; ?>" data-term_id="<? echo $term['id']; ?>"><? echo $term['title']; ?> (<? echo count($term['items']); ?>)</a></li>
							   <? endforeach; ?> 
							</ul>
						</div>
					</div>
				    
				    <div class="col-lg-9 col-md-9 col-sm-8 col-xs-12 resset_pad" >
					    <div class="check_category_bl" >
							<h3 class="title_add_lib" >choose text</h3>
							<div class="scr_add scrollbar" id="library_text">
								<? foreach($data->get('data') as $term): ?>
<? if(empty($term['items'])) continue; ?>
<div class="table-responsive" id="table-posts-<? echo $term['id']; ?>">
									<table class="list_add_bl table"> 
										<tbody>
<? foreach($term['items'] as $post): ?>											
<tr>
												<td>
<? echo $post->post_content; ?>
												</td>
												<td>
													<div class="lifted_add">
													<a href="#" data-post_id="<? echo $post->ID; ?>" class="add_post_to_constructor">add</a>
													</div>
												</td>
											</tr>
<? endforeach; ?>
											
										</tbody>
									</table>
								</div>
<? endforeach; ?>	
							</div>
</div></div>
<div class="row redact_lib_big" >
				    <form method="post" id="posts_list_form">
				        
				</form>
					<div class="lifted_7">					    
<a href="#" id="submit_posts_form" class="primer3">Proceed with selected text</a>
				    </div>
				</div>
