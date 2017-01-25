<?
$this->include_css('jquery-colorpicker', get_template_directory_uri() . '/css/spectrum.css');
$this->include_js('jquery-colorpicker', get_template_directory_uri() . '/js/spectrum.js');
?>
<script type="text/javascript">
jQuery(function($){
	$('#constructor_form').submit(function(){
		var action = $(this).find('input[name="constructor[action]"]').val();
		var data = $(this).serialize();	

		$.ajax({
			url: '<? echo $this->ajax_url('send_form'); ?>&',
			data: data,
			method: 'post',
			dataType: 'json',
			success: function(response) {
				window.location.href = '/cart/';
				$('#constructor_form_result').html(response.data.html);
				if(response.data.product_id)
					$('#constructor_product_id').val(response.data.product_id);
				/*$.fancybox({
					href: '#constructor_form_result'
				});*/
			}
		});


		return false;
	});
	
});
</script>
<div style="display: none;">
<div id="constructor_form_result">
</div>
</div>
			<div class="maker">
				<form action="" method="POST" id="constructor_form">
				<div class="container default">
				<input type="hidden" name="constructor[font_color]" value="<? echo @$scarf_data['font_color']; ?>" id="constructor_font_color">
				<input type="hidden" name="constructor[action]" value="add_to_cart">
				<input type="hidden" name="constructor[product_id]" value="<? echo @$scarf_data['product_id']?>" id="constructor_product_id">
				<div class="cap-wrap">
					<h2 class="title_ideas">MAKE YOUR SCARF</h2>
				</div>
				
					<div class="row">
						<div class="col-sm-4">
							<div class="font-style-box">
								<span class="m-title"><i class="m-marker">1</i>Choose FONT</span>
								<div class="fsl-wrap">
<? foreach($fonts as $font_code => $font_data): ?>
<label style="<? echo $font_data['style']; ?>">
<input <? if($font_code == $scarf_data['font']): ?>checked="checked"<? endif; ?> class="f-styler" type="radio" name="constructor[font]" value="<? echo $font_code; ?>"><? echo $font_data['title']; ?></label>
<? endforeach; ?>

								</div>
							</div>
						</div>
						<div class="col-sm-4  decor-line">
							<div class="font-color-box">
								<span class="m-title"><i class="m-marker">2</i>Choose Font color</span>
								<div class="text-center">
									
<label class="black">
<input <? if(@$scarf_data['font_color'] == '000000' || empty($scarf_data['font_color'])): ?>checked="checked"<? endif; ?> class="f-styler font_color_select" type="radio" name="constructor[font_color_select]" value="000000">
<span>black</span>
</label>
<label class="multicolor">
<input class="f-styler font_color_select" type="radio" <? if(!empty($scarf_data['font_color']) && $scarf_data['font_color'] != '000000'): ?>checked="checked"<? endif; ?> name="constructor[font_color_select]" class="font_color_select" value="custom" id="font_color_select_custom">
<span>color</span></label>
								</div>
							</div>
						</div>
						<div class="col-sm-4">
							<div class="font-color-box">
								<span class="m-title"><i class="m-marker">3</i>Choose Scarf color</span>
								<div class="text-center">

									<label class="white">
										<input <? if(@$scarf_data['background_color'] == '000000' || empty($scarf_data['background_color'])): ?>checked="checked"<? endif; ?> class="f-styler" type="radio" name="constructor[background_color]" value="ffffff">
										<span>white</span>
									</label>
									<label class="biege">
									<input class="f-styler" type="radio" <? if(!empty($scarf_data['background_color']) && $scarf_data['background_color'] != '000000'): ?>checked="checked"<? endif; ?> name="constructor[background_color]" class="" value="<? echo $this->args()->get('default_biege_code'); ?>" id="">
										<span>biege</span></label>
				
								</div>
							</div>
						</div>
					</div>
					
					
					<div class="row">
						<div class="col-sm-12">
							<div class="m-box" style="padding: 10px; height: auto;">
								<div class="row">
									<div class="col-sm-4">
										<span class="mp-title">part of Scarf preview</span>
									</div>
									<div class="col-sm-8">
										<div class="m-preview" style="background-color: #e0d9cc;">
											<span style="color: #676666; font-family: 'MyUnderwood'; font-size: 13px; font-weight: 400; line-height: 22px;">
												Our distinctive scarves offer unlimited ways to thank clients, reward employees and even advertise your company in unique ways. Through our convenient customization process and dedicated customer service, you can arrange for large orders and enjoy a first-class process from start-to-finish. Our distinctive scarves offer unlimited ways to thank clients, reward employees and even advertise your company in unique ways. Through our convenient customization process and dedicated customer service, you can arrange for large orders and enjoy a first-class process from start-to-finish. Our distinctive scarves offer unlimited ways to thank clients, reward employees and even advertise your company in unique ways. Through our convenient customization process and dedicated customer service, you can arrange for large orders and enjoy a first-class process from start-to-finish.
											</span>
										</div>
									</div>
								</div>
								
								
							</div>
						</div>
					</div>
					
					
					
					
					
					
				
			</div>
			<div class="g-box">
				<div class="container">
				<div class="row">
					<div class="col-sm-8">
						<div class="text-in-wrap">
							<span class="m-title"><i class="m-marker">4</i>SUBMIT TEXT</span>
<? @wp_editor($scarf_data['content'], 'constructor_content', array(
	'media_buttons' => false,
	'wpautop' => false,
	'quicktags' => false,
	'tinymce' => array(	
		'plugins' => '' 
	),
	'textarea_name' => 'constructor[content]',
	'editor_height' => 250
)); ?>	
						</div>
					</div>
					<div class="col-sm-4">
						
						<div class="select_or_bl">
							<ul class="list_or_sel">
								<li class="text_or_sel">or</li>
								<li><img alt="" src="<? echo get_template_directory_uri(); ?>/images/start/or.png"></li>
								<li class="text_or_sel">SELECT TEXT in</li>
								<li>
									<div class="lifted_sel">
									<a href="<? echo $this->args()->get('text_library_url', '/text-library'); ?>" class="">the TEXT LIBRARY</a>
									</div>
								</li>
							</ul>
						</div>
						
					</div>
				</div>
				</div>
			</div>
			
			
				<div class="container">
					<div class="bo-wrap">
					<div class="row">
						<div class="col-md-4 col-sm-6">
							<div class="bo-col">
								<span class="title-ever">CHOOSE SCARF STYLE</span>
								<div class="other_ch_bl">
									<ul class="list_other_ch_bl">
										<li data-type-s="straight">
											<div class="back_tr">
											<input class="wide-select" <? if(empty($scarf_data['style']) || $scarf_data['style'] == 'straight'): ?>checked="checked"<? endif; ?> type="radio" name="constructor[style]" id="c" value="straight">
												<label for="c">
												<img alt="" src="<? echo get_template_directory_uri(); ?>/images/start/str.png">
													<p class="text_other_ch_bl"><i></i>straight</p> 
												</label>
											</div>
										</li>
										<li data-type-s="infinity">
											<div class="back_tr">
											<input class="wide-select" <? if(!empty($scarf_data['style']) && $scarf_data['style'] == 'infinity'): ?>checked="checked"<? endif; ?> type="radio" name="constructor[style]" value="infinity" id="c2">
												<label for="c2">
												<img alt="" src="<? echo get_template_directory_uri(); ?>/images/start/inf.png">
													<p class="text_other_ch_bl"><i></i>infinity</p> 
												</label>
											</div>
										</li>
									</ul>
								</div>
							</div>
						</div>
						<div class="col-md-4 col-sm-6">
							<div class="bo-col">
							
								<span class="title-ever">ARTWORK APPROVAL</span>
								<span class="sub-title-ever">Send me my artwork file to approval:</span>
								<div class="other_ch_bl">
									<ul class="list_other_yes_no">
										<li>
											<div class="back_tr">
											<input class="wide-select" <? if(empty($scarf_data['artwork_approval']) || $scarf_data['artwork_approval'] == 'yes'): ?>checked="checked"<? endif; ?> type="radio" name="constructor[artwork_approval]" id="e" value="yes">
												<label for="e">
													<p class="text_other_ch_bl"><span>yes</span><i></i></p> 
												</label>
											</div>
										</li>
										<li>
											<div class="back_tr">
											<input class="wide-select" <? if(!empty($scarf_data) && $scarf_data['artwork_approval'] == 'no'): ?> checked="checked" <? endif; ?>type="radio" name="constructor[artwork_approval]" value="no" id="e2">
												<label for="e2">	
													<p class="text_other_ch_bl"><span>no</span><i></i></p> 
												</label>
											</div>
										</li>
									</ul>
								</div>
							</div>
						</div>
						<div class="col-md-4 col-sm-12">
							<div class="bo-col">
								<span class="title-ever no-border">OTHER INSTRUCTIONS</span>
								<textarea name="constructor[other_instructions]" placeholder="write your instruction here"><? echo @$scarf_data['other_instructions']; ?></textarea>
								
							</div>
						</div>
					</div>
						<p class="text-center"><button type="submit" class="btn btn-style">add to cart</button></p>
				</div>
			</div>
				
			</form>
		</div>
