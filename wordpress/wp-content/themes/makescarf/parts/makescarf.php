<script type="text/javascript">
hexDigits = new Array
        ("0","1","2","3","4","5","6","7","8","9","a","b","c","d","e","f"); 

//Function to convert hex format to a rgb color
function rgb2hex(rgb) {
 rgb = rgb.match(/^rgb\((\d+),\s*(\d+),\s*(\d+)\)$/);
 return "#" + hex(rgb[1]) + hex(rgb[2]) + hex(rgb[3]);
}

function hex(x) {
  return isNaN(x) ? "00" : hexDigits[(x - x % 16) / 16] + hexDigits[x % 16];
}
var BackgroundChanged = function(hex) {
	jQuery('#live-preview').css('background-color', hex);
	if(!jQuery('.background-select').hasClass('activeCheck'))
		jQuery('.background-select').addClass('activeCheck');
	jQuery('#scarf_background_color').val(hex);
}
var FontColorChanged = function(hex) {
	jQuery('#live-preview').css('color', hex);
	if(!jQuery('.fontcolor-select').hasClass('activeCheck'))
		jQuery('.fontcolor-select').addClass('activeCheck');
	jQuery('#scarf_text_color').val(hex);
}
jQuery(function($){
	$('#live-preview').css('background-color', '#000000');
	$('#scarf_background_color').val('#000000');	
	$('.colorpickerHolder').ColorPicker(
		{
		flat: true,
			color: '#000000',
			onChange: function(hsb, hex, rgb){
				FontColorChanged('#'+hex);	
			}
			
		}
	);
		
	$('.background-color-choise').change(function(){
		if(!$('.background-select').hasClass('activeCheck'))
			$('.background-select').addClass('activeCheck');	
		$('#live-preview').css('background-color', $(this).val());
	});
	//$('.background-color-choise:first-child').trigger('change');
	$('.font-variant').change(function(){
		if(!$('.font-select').hasClass('activeCheck'))
			$('.font-select').addClass('activeCheck');
		$('#live-preview').css('font-family', $(this).val());
		$('#live-preview').css({ 'font-size': $(this).data('font-size'), 'line-height': $(this).data('line-height')});
	});
	$('.preset').click(function(){
		var background = $(this).data('background-color');
		var text_color = $(this).data('color');
		$('.colorpickerHolder').ColorPickerSetColor(background);
		BackgroundChanged(background);
		$('.text-color-select').removeAttr('checked');
		$('#text_color_custom').attr('checked', 'checked');
		$('#live-preview').css('color', text_color);
		$('#scarf_text_color').val(text_color);
		if(!$('.textcolor-select').hasClass('activeCheck'))
			$('.textcolor-select').addClass('activeCheck');
		return false;
	});
	
	$('#send_yes, #send_no').change(function(){
		if(!$('#artwork_send').hasClass('activeCheck'))
		$('#artwork_send').addClass('activeCheck');
	});
	$('#instructions-toggle').click(function(){
		$('#instructions').toggle('slow');
		return false;
	});
	$('#instructions_area').keyup(function(){
		if('' != $(this).val())
			$('#no_instructions').hide();
		else 
			$('#no_instructions').show();
	});
	$('.font-variant:checked').trigger('change');
	$('.font-select').removeClass('activeCheck');
		
	$('.preset:first').trigger('click');
	$('#go-to-library').click(function(){
		var href = $(this).attr('href');
		var data = { data: {
			background_color: rgb2hex($('#live-preview').css('background-color')),
				text_color: rgb2hex($('#live-preview').css('color')),
 'font_variant': $('#live-preview').css('font-family') } };
		var url = href + '?' + $.param(data);
		window.location = url;
		return false;
	});
<? if(!empty($data['background_color'])): ?>
	$('.colorpickerHolder').ColorPickerSetColor('<? echo ($b = urldecode(strip_tags($data['background_color']))); ?>');
	BackgroundChanged('<?=$b?>');
<? endif; ?>
<? if(!empty($data['text_color'])): ?>
	$('#ltext-color-custom').ColorPickerSetColor('<? echo ($b = urldecode(strip_tags($data['text_color']))); ?>');
	$('#live-preview').css('color', '<?=$b; ?>');
	$('#scarf_text_color').val('<?=$b?>')
<? endif; ?>
<? if(!empty($data['font_variant'])): ?>
	$('.fontVariants').find('input[value="<? echo urldecode(strip_tags($data['font_variant'])); ?>"]').attr("checked", "checked").trigger('change');	
<? endif; ?>
})
</script>
<? if(!empty($wp_errors->errors)): foreach($wp_errors->errors as $error): ?>
<p class="notice error"><?=$error[0]; ?></p>
<? endforeach; endif ?>
<form action="" method="post" class="choiseForm">
<input type="hidden" name="scarf[text_color]" value="" id="scarf_text_color" />
<input type="hidden" name="scarf[background_color]" id="scarf_background_color" />
<input type="hidden" name="scarf_action" value="save_scarf" />
<input type="hidden" name="scarf[items_count]" value="1" />
					<div class="choiseWrap">
						<p class="stepTitle"><span class="chtOne"></span>CHOOSE COLOR </p>
						<p class="stepTitle"><span class="chtTwo"></span>CHOOSE FONT & FONT COLOR</p>
						<div class="wrapLivePreview">
							<div class="livePreview">
								<p><a href="javascript://" style="cursor: default; text-decoration: none; color: #6e5243; ">Scarf sample live-preview:</a></p>
<div style="display: none;" id="lorem_orig">
<?=$this->options->GetOption('preview_text'); ?>
</div>								
<div id="live-preview" style="padding: 25px 0 0 38px; height: 229px; overflow: hidden; color: #fff; font: 18px/22px ZapfinoForteL, serif; text-align: justify;">
<?=$this->options->GetOption('preview_text'); ?>								</div>
							</div>
						</div>
						<div class="choiseBox">							
							<div class="choiseFont">
								<p>Choose color<span class="inactiveCheck font-select"></span></p>
								<p class="fontVariants">
									<span class="varOne"><input data-line-height="34px" data-font-size="32px" type="radio" class="font-variant" name="scarf[font_variant]" checked id="font_ZapfinoForteL" value="ZapfinoForteL"/><label for="font_ZapfinoForteL">Hand Writing</label></span>
									<span class="varTwo"><input data-line-height="22px" data-font-size="18px" type="radio" name="scarf[font_variant]" class="font-variant" id="font_Gothic" value="Gothic" /><label for="font_Gothic">Modern</label></span>
									<span class="varThree"><input data-line-height="22px" data-font-size="18px" type="radio" name="scarf[font_variant]" class="font-variant" id="font_MyUnderwood" value="MyUnderwood" /><label for="font_MyUnderwood">Book Typewriting</label></span></p>
								<p>Choose color of scarf's background:<span class="inactiveCheck background-select"></span></p>
								<ul class="colorFont">
									<li>
										<label for="background_color_white">
											<img src="<? echo get_template_directory_uri(); ?>/img/color1.png" alt="" />
											White
	<input  type="radio" name="scarf[background_color]" checked name="background_color" id="background_color_white" value="#ffffff" class="background-color-choise" />									
</label>
										
									</li>
									<li>
										<label for="background_color_black">
											<span class="circle" style="margin: auto; width: 54px; height: 54px; background-color: #e1dacd"></span>
<div style="clear: both;"></div>	
											<span style="margin: 5px; display: block;">Taupe</span>
											
										<input  type="radio" name="scarf[background_color]" class="background-color-choise" value="#e1dacd" style="margin: 8px auto;" id="background_color_taupe" />
										</label>
									</li>	
								</ul>
							</div>
							<p> Font color:<span class="fontcolor-select inactiveCheck"></span></p>
							<p class="colorpickerHolder"></p>
						</div>
					</div>
<div class="popularStyles">
						<p class="popularTitle"><a href="javascript://" style="text-decoration: none; cursor: default;">Popular styles:</a></p>
						<p>
<? MakeScarf_Theme::Me()->DisplayTemplatePart('presets_short_list.php'); ?>							
						</p>
<p style="margin: 10px; color: #1037d1; text-transform: capitalize;"><input type="checkbox" name="save_colors" value="Y" />&nbsp;&nbsp;SAVE SELECTED COLORS</p>
					</div>
					
					<div class="bottonSteps">
						<p class="stepTitle"><span class="chtThree"></span>Submit text  </p>
						<p>Submit your text here or choose from our “scarves text idias”:<span id="scarf_text_label" class="inactiveCheck"></span></p>
						<div class="wrapPanelSub">
					<a href="<? echo get_term_link(LIBRARY_TAX_ID, 'category'); ?>" id="go-to-library"><span class="libreryBtn">Select text in TEXT LIBRARY</span></a>
							<span class="or">or</span>
<textarea id="scarf_text" name="scarf[text]" required="required"><? echo $text; ?></textarea>

						<div>
<style>
.scarfStyle {
	margin: 10px;
	vertical-align: middle !important;

}
.scarfStyle label {
	float: left;
	text-align: center;
	margin-left: 20px;
}
.scarfStyle p span {
	display: block;
}
.scarfStyle img {
} 
</style>
<div class="scarfStyle" style="clear: both;">
<p class="stepTitle">Choose scarf style</p>
<label for="style_straight">
<input type="radio" checked name="scarf[scarf_style]" value="straight" id="style_straight" /><img src="<?=get_template_directory_uri();?>/img/straight.png" /><p>STRAIGHT <span>(regular)</span></p>
</label>
<label for="style_infinity">
<input type="radio" name="scarf[scarf_style]" value="infinity" id="style_infinity" /><img src="<?=get_template_directory_uri(); ?>/img/infinity.png"><p>INFINITY <span>(ends are sewn into a circle)<br />(Additional $5)</span></p>
</label>
</div>
<div style="clear: both;display: block;
height: 20px; "></div>
						<p class="stepTitle">Artwork Approval </p>
						<p class="innerSpan">Send me my artwork file to approval:<span id="artwork_send" class="inactiveCheck"></span>
							<span><input  type="radio" name="scarf[send_artwork]" checked value="yes" id="send_yes" /><label for="send_yes">Yes</label></span>
							<span><input  type="radio" name="scarf[send_artwork]" value="no" id="send_no" /><label for="send_no">No</label></span>
						</p>
						<p class="stepTitle">Other instructions</p>
						<p class="noneArt"><span id="no_instructions">None entered </span><a href="#" id="instructions-toggle">Edit instructions</a></p>
						<p id="instructions" style="display: none;"><textarea id="instructions_area" name="scarf[instructions]"></textarea></p>
					</div>
					<div class="wrapBtn">
						<button type="submit" name="add_to_cart" class="btn">Add to cart</button>
					</div>
				</form>
<script type="text/javascript">
jQuery(function($){

	$('#scarf_text').tooltip();
	//$('#scarf_text').markItUp(mySettings);	
	init_sceditor('#scarf_text', '<?=get_template_directory_uri();?>/sceditor/jquery.sceditor.default.min.css', { width: '500px'});
	
});
</script>
