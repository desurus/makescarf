<?
/*
 * Теперь я могу писать вот просто вот так :D вместо подключения скриптов в отдельных файлах.
 * **/
$this->include_css('constructor-page', $this->get_template_directory_uri() . '/css/light.css');
$this->include_css('constructor-tmce', $this->get_template_directory_uri() . '/css/constructor.css');

$this->require_lib('featherlight');

if(!is_user_logged_in()) {
	//echo "Not logged in!";
}
?>
<script type="text/javascript">
jQuery(function($){
<? if(!PM()->User()->is_logged_in()): ?>
	$('#scarf_constructor_form').submit(function(){
		var action = $(this).find('input[name="constructor[action]"]').val();
		var data = $(this).serialize();	

		data += '&scarf_body_html='+tinymce.activeEditor.getContent();	
		$.ajax({
			url: '<? echo $this->ajax_url('save_scarf_cookie'); ?>&',
				data: data,
				method: 'post',
				dataType: 'json',
				success: function(response) {
						
				}
		});
		$.featherlight({
			target: '#login_wrapper'
		});
		return false;
	});

<? else: ?>
	$('#scarf_constructor_form').submit(function(){
		var action = $(this).find('input[name="constructor[action]"]').val();
		var data = $(this).serialize();	

		data += '&form_action='+action+'&scarf_body_html='+tinymce.activeEditor.getContent();	
		$.ajax({
			url: '<? echo $this->ajax_url('send_form'); ?>&',
				data: data,
				method: 'post',
				dataType: 'json',
				success: function(response) {
					$('#constructor_form_result').html(response.html);
					$('#constructor_product_id').val(response.product_id);
					$.featherlight({
						target: '#constructor_form_result'
					});
				}
		});


		return false;
	});
<? endif; ?>
	$('#constructor_add_to_cart_btn').click(function(){
		$('input[name="constructor[action]"]').val('add_to_cart');
		$('#scarf_constructor_form').trigger('submit');
		return false;
	});
	$('#constructor_save_btn').click(function(){
		$('input[name="constructor[action]"]').val('save');
		$('#scarf_constructor_form').trigger('submit');
		return false;
	});
	$('#font_size_set').click(function(){
		
	});
	$('#font_size_dev').change(function(){
		//$('#font_size_set').trigger('click');
		var font_size_dev = parseFloat($('#font_size_dev').val());
		var value = font_size_dev.toString() + 'in';
		
		tinymce.activeEditor.execCommand('FontSize', null, value);
		return false;
	});
});
</script>
<div class="dev_box_temp" id="dev_box_temp">
<input type="text" name="font_size_dev" id="font_size_dev" value="" placeholder="Set this font size in INCHES." />
<input type="button" name="font_size_set" id="font_size_set" value="Set!" class="btn btn-default" />
<? if($data->get('preview_url')): ?>
<p>Scarf <a href="<? echo $data->get('preview_url'); ?>" target="_blank">preview & print</a>.</p>
<? endif; ?>
</div>
<div id="result_hidden" style="display: none;">
<div id="constructor_form_result">
<? $this->display_template('login.php'); ?>
</div>
</div>
<form action="" method="post" id="scarf_constructor_form">

<? $constructor = $data->get('constructor', array()); ?>
<input type="hidden" name="constructor[layout]" value="<? echo @$constructor['layout']; ?>" />
	<input type="hidden" name="constructor[color]" value="<? echo @$constructor['color']; ?>" />
<input type="hidden" name="constructor[fontsize]" value="<? echo @$constructor['fontsize']; ?>" /> 
<input type="hidden" name="constructor[font]" value="<? echo @$constructor['font']; ?>" />
<input type="hidden" name="constructor[action]" value="save" />
<input type="hidden" name="constructor[scarf_id]" value="<? echo $data->get('scarf_id'); ?>">
<? if(@$constructor['skip_default_styles']): ?>
<input type="hidden" name="constructor[skip_default_styles]" value="yes" />
<? else: ?>
<input type="hidden" name="constructor[skip_default_styles]" value="no" />
<? endif; ?>
<input type="hidden" name="constructor[product_id]" value="<? echo @$constructor['product_id']?>" id="constructor_product_id" />
	<div class="constructor">
	    <div class="container constructor-border">
		<!-- constructor edit area -->
		<div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
		<div class="constructor-area">
		    <div class="constructor-scarf">
			<div class="constructor-scarf-menu">

			</div>
			<div class="scarf-text">
<?
function buttons_panel_top($buttons){
	$buttons = array(
		'constructor_layout_select',
		'constructor_colors_list_button',
		//'constructor_font_select',
		'fontsizeselect',
		'forecolor',
		'fontselect',
		'bold',
		'separator',
		'italic',
		'strikethrough',
		'alignleft',
		'aligncenter',
		'alignright',
		'alignjustify',
		'fullscreen',	
	);
	return $buttons;
}
function buttons_panel_middle($buttons) {

	$buttons = array();	
	//$buttons[] = 'constructor_colors_list_button';
	return $buttons;

}
add_filter('mce_buttons', 'buttons_panel_top');
add_filter('mce_buttons_2', 'buttons_panel_middle');
?>
<script type="text/javascript">
jQuery(function($){
	$('#scarf-editor').attr("placeholder", "Enter your text here");
});
</script>
<? wp_editor($editor['text'], 'scarf-editor', array(
	'wpautop' => false,
	'media_buttons' => false,
	'quicktags' => false
)); ?>		
<p><strong>Output CANVAS element goes here:</strong></p>
<canvas id="constructor_output"></div>
			    <div class="toCart add-to-cart">
    <div class="lifted_yes_no add-to-cart-buttons">
	<input id="constructor_add_to_cart_btn" type="submit" value="Add to Cart" title="Add to Cart" class="button btn-cart">
	     </div>   
</div>
			</div>
		    </div>    
		</div>
		</div>
		<!-- /constructor edit area -->
		<!-- /constructor sidebar -->
		<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
		<div class="constructor-sidebar">
		    <div class="select_or_bl">
			<ul class="list_or_sel">
			    <li class="text_or_sel">SELECT TEXT in</li>
			    <li><img alt="" src="<? echo get_template_directory_uri(); ?>/images/start/or.png"></li>
			    <li>
				<div class="lifted_sel">
				<a href="/text-library/" class="">the TEXT LIBRARY</a>
				</div>
			    </li>
			</ul>
		    </div>

		    <div class="backgr_bl">
						    <h3 class="title_ever_other_ch_bl">CHOOSE SCARF STYLE</h3>
						    <div class="other_ch_bl">
								<ul class="list_other_ch_bl">
									<li data-type-s="straight">
									    <div class="back_tr">
									    <input type="radio" name="constructor[style]" id="c" <? if($constructor['style'] == 'straight'): ?>checked="checked"<? endif; ?> value="straight">
											<label for="c">
											<img alt="" src="<? echo get_template_directory_uri(); ?>/images/start/str.png">
												<p class="text_other_ch_bl"><i></i>straight</p> 
											</label>
										</div>
									</li>
									<li data-type-s="infinity">
									    <div class="back_white back_tr">
									    <input type="radio" <? if($constructor['style'] == 'infinity'): ?>checked="checked"<? endif; ?> name="constructor[style]" value="infinity" id="c2">
											<label for="c2">
											<img alt="" src="<? echo get_template_directory_uri(); ?>/images/start/inf.png">
												<p class="text_other_ch_bl"><i></i>infinity</p> 
											</label>
										</div>
									</li>
								</ul>
						    </div>
						</div>
			<div class="constructor-save">
			    <span class="save-button-wrapp">
				<span class="save-button" data-type="save-layout" id="constructor_save_btn">SAVE/SEND</span>
			    </span>
			</div>
		</div>
		<!-- /constructor sidebar -->
	    </div>
	</div>
	</div>

	    </form>
