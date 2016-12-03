// JavaScript Document

jQuery(function($){

	$('.colorpickerHolder').ColorPicker({flat: true});

	$('#myButton').click(function(e) {
          e.preventDefault();
	  $('#myModal').reveal();
     });
		
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
        }
   });
});
