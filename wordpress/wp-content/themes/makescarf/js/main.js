// JavaScript Document

jQuery(function($){

			
	/* PIE */
	if (window.PIE) {
		$('.flex-direction-nav a,.textIdeas ul img,.flex-control-paging li a ').each(function() {
		PIE.attach(this);
		});
	}
});//end ready

jQuery(function($){
      $('.flexslider').flexslider({
        animation: "slide",
        start: function(slider){
          $('body').removeClass('loading');
        },
	      slideshow: false
   });
});
function init_sceditor(selector, style_url, args) {
	var defaults = {
		width: 'auto',
		height: 'auto',
		toolbar: "bold,italic,underline,strike|left,center,right"
	};
	args = jQuery.extend(defaults, args);
	var instance = jQuery(selector).sceditor({
		        plugins: "xhtml",
			style: style_url,
			toolbar: args.toolbar,
			emoticonsEnabled: false,
			style: '/wp-content/themes/makescarf/sceditor/jquery.sceditor.scarf.min.css',			
    			width: args.width,
			height: args.height
	});
return instance;
}
//Script for changing scarf styles
jQuery(function($){
	$('.scarf-style').change(function(){
		var style = $(this).val();
		if(style != 'infinity') {
			//remove label
			$(this).next('.inf-label').hide();
		} else {
			$(this).next('.inf-label').show();
		}
		var item_id = $(this).data('scarf-id');
		$.ajax({
			url: scarf_ajax_url,
			data: { action: 'modify_style', item_id: item_id, style: style },
			dataType: 'json',
			success: function(data) {
				if(parseInt(data.status) == 0) {
					$('#total-amount').text(data.price);
				} else {
					alert("An error occured while modify the data in cart, please try later.");
				}
			}
		});
	});
})
