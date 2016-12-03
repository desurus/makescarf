jQuery(document).ready(function($){
	if(jQuery(".scrollbar").length) {
		var news = document.querySelectorAll(".scrollbar");

		for(var i = 0; i < news.length; i++) {
			Ps.initialize(news[i], {
				wheelSpeed: 2,
				wheelPropagation: true,
				minScrollbarLength: 20
			});
		}
	}
})
