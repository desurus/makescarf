jQuery(function($){
	var constructor_form = $('#constructor_form');
	var updateLivePreview = function() {
		var data = $(constructor_form).serializeArray();	
		var font, font_color, background_color = false;
		$(data).each(function(i, o){
			if(o.name == 'constructor[font]')
				font = o.value;
			if(o.name == 'constructor[font_color]')
				font_color = o.value;
			if(o.name == 'constructor[background_color]')
				background_color = o.value;
		});	

		$('.m-preview').find('span').css({
			'font-family': font	
		});
		$('.m-preview').find('span').css({
			'color': '#' + font_color	
		});
		$('.m-preview').css({
			'background-color': '#' + background_color.toString()
		});

		if (font == 'ZapfinoForteL') {
			$('.m-preview > span').css('font-size', '27px');
		} else {
			$('.m-preview > span').css('font-size', '13px');
		}
	}
	
	

	/*$(document).click(function(e){
		var parents = $(e.target).parents();
		var hide_pckr = true;
		$(parents).each(function(i, el){
			if($(el).hasClass('colorpicker')) hide_pckr = false;
			if($(el).hasClass('multicolor')) hide_pckr = false;
		});
		if(hide_pckr) $('#font_color_select_custom_trigger').fadeOut();
	});*/
	$('.font_color_select').change(function(){
		if($(this).attr('checked')) {
			if($(this).val() == 'custom') {//Show a color select
				//$('#font_color_select_custom-styler').click();
				//$('#font_color_select_custom_trigger').fadeIn();
			} else {
				//Thsi is some present value so we just set it, and trigger an preview update
				$('#constructor_font_color').val($(this).val());
				$('#constructor_font_color').trigger('change');
			}
		}
	});

	$('.multicolor').spectrum({
		//flat: true,
		move: function(color) {
			$('#constructor_font_color').val(color.toHex());
			$('#constructor_font_color').trigger('change');
		},
		hide: function(color) {
			$('#constructor_font_color').val(color.toHex());
			$('#constructor_font_color').trigger('change');
			$(this).val('custom');
		},
		//preferredFormat: "hex",
	});
	//$('.multicolor').spectrum('show');
	$('#constructor_form').find('input[type="radio"]').change(function(){
		updateLivePreview();
	});
	/*$('#font_color_select_custom_trigger').appendTo('body');

	$('#font_color_select_custom_trigger').css({
		left: $('#font_color_select_custom').offset().left + 70 + 'px',
		top: $('#font_color_select_custom').offset().top  + 'px'
	});*/
	
	$('#constructor_font_color').change(function(){
		updateLivePreview();
	});
	$('select, .f-styler').styler();
	updateLivePreview();
	//Small hack in scarf styles selectors:
	$('.wide-select').each(function(){
		if($(this).is(':checked')) {
			$(this).parent().addClass('back_white');
		}		
	});
	//$('.font_color_select').trigger('change');
});
/**** back_white back_white ****/
