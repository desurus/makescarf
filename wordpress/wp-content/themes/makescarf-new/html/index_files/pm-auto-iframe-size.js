document.addEventListener("DOMContentLoaded", function(ev){
	setTimeout(function(){
		var body = document.body;
		var html = document.documentElement;
		var height = Math.max( body.scrollHeight, body.offsetHeight);		
		var width = Math.max( body.scrollWidth, body.offsetWidth);
		parent.PMUtils.resizeFeatherlightIframe(width, height);
	}, 100)	;
});
