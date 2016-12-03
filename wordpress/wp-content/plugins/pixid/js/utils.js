var PMUtils = {
	replaceAll: function(str, find, replace) {
		return str.replace(new RegExp(this.escapeRegExp(find), 'g'), replace);
	},
	escapeRegExp: function(str) {
		return str.replace(/([.*+?^=!:${}()|\[\]\/\\])/g, "\\$1");
	},

	resizeFeatherlightIframe: function(width, height) {
		var iframe = document.getElementsByClassName('featherlight-inner');
		
		if(iframe.length > 0) {
			iframe[0].width = width;
			iframe[0].height = height;
		}
	}
	
}
window.PMUtils = PMUtils;
