jQuery(document).ready(function(){
	jQuery("#carousel_default").owlCarousel({
		autoPlay: 3000, //Set AutoPlay to 3 seconds
		items : 5,
		navigation : true,
		navigationText: [
			"<i class='icon-chevron-left icon-white'><</i>",
			"<i class='icon-chevron-right icon-white'>></i>"
		] 
	});
})
