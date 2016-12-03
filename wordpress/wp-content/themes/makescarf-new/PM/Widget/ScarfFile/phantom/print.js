var system = require("system");
var args = system.args;
var url = args[1];
var output = args[2];

var page = require("webpage").create();
page.paperSize = {
	width: '65in',
	height: '27in'
};
page.open(url, function() {
	console.log("Complete!");
	page.render(output);
	page.close();
	phantom.exit();
});
