<? get_header(); ?>
<h1>The Text library</h1>
				<div class="asideMenu">
					<h2>Our text library</h2>
					<ul><?
$category = get_category(get_query_var('cat'));
$args = array(
	'child_of' => LIBRARY_TAX_ID,
	'echo' => 0,
	'hierarchical' => 0,
	'title_li' => '',
	'current_category' => $category->cat_ID
	);
	$list = wp_list_categories($args);
	echo str_replace('current-cat', 'current-menu-item', $list);	
?></ul>
				</div>
<? if(!empty($_GET['data'])): ?>
	<script type="text/javascript">
	jQuery(function($){
		var append = '<? echo strip_tags($_SERVER['QUERY_STRING']); ?>';
		$('.cat-item a').each(function(i, el){
			$(el).attr('href', $(el).attr('href') + '?' + append);
		});
	});	
</script>
<? endif; ?>
				<div class="main">
					<section class="slider">
						<div class="flexslider">
							<ul class="slides">
<?
	$_posts = $_ = array();
	while(have_posts()): the_post(); global $post; $_posts[] = $post;  endwhile;
	$i = $j =1;
	//crazy code :(
	foreach($_posts as $_post) {
		if($j > 5) {
			$i++;
			$j = 1;
		}
		$j++;
		$_[$i][] = $_post;
				
	}
	$i = 0;
	$j = 0;
?>
<li><h2>Choose Text</h2>
<? foreach($_ as $section): 
if(1 == $i / 3): $i = 0; ?></li><li><h2>Choose text</h2><? endif; ?>								
<p>
<? foreach($section as $_post): ?>
<span><a class="post-link" href="#" data-post-id="<? echo $_post->ID; ?>"><? echo $_post->post_title; ?></a></span>
<? endforeach; ?>
</p>							
<? //if($i == 1) echo "</li>"; ?>
<? $i++; endforeach; ?>
</li>
							</ul>
						</div>
<form action="<? echo get_permalink(MAKE_SCARF_PAGE_ID); ?>" method="post">

						<h3>Name of text</h3>
						<textarea name="text" class="scrollBox poem">
							
						</textarea>
					</section>
				</div>
				<div class="wrapBtn">
									
<? if(!empty($_GET['data'])): foreach($_GET['data'] as $key => $value): ?>
<input type="hidden" name="<? echo strip_tags($key)?>" value="<? echo urldecode(strip_tags($value)); ?>" />
<? endforeach; endif; ?>
<button type="submit" class="btn">Proceed with selected text</button>
<input type="hidden" id="lid" name="lid" value="" />
</form>
				</div>
<? foreach($_posts as $_post): ?>
<div style="display: none;" class="poem" id="post-<? echo $_post->ID; ?>">
<? echo nl2br($_post->post_content); ?>
</div>
<? endforeach; ?>
<script type="text/javascript">

var editor = init_sceditor('.scrollBox', '<?=get_template_directory_uri();?>/sceditor/jquery.sceditor.default.min.css', { width: '98%'});
jQuery(function($){
	
	$('.post-link').click(function(){
		var html = $('#post-'+$(this).data('post-id')).html();
		$('#lid').val($(this).data('post-id'));
		$('.scrollBox').sceditor('instance').setWysiwygEditorValue(html);	
		return false;
	});
	$('.post-link:first').trigger('click');
});
</script>
<? get_footer(); ?>
