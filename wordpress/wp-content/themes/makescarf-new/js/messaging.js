jQuery(function($){
	if($('#fep-message-top').length == 0) return;
	$('#fep-message-to').val("1");
	$('#fep-message-top').val("Support");
	$('#fep-message-top').attr("disabled", true);
})
