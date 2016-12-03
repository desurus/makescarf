/**
 * Plugin name: PMFixTmpMCE
 * Description: http://track.hosted.pw/issues/132
 * Now we allow div container and much more elements in the editor. So now we gets a lot of restricted elements, such as described in the task
 * @version 0.1
 * @author Shell
 * */

(function() {	
	tinymce.create('tinymce.plugins.PMFixTmpMCE', {
		init: function(editor) {
			editor.on("BeforeSetContent", function(e){
				var content = e.content;
					
				console.log(content);	
				return e;
			});
			
		}
	});
	tinymce.PluginManager.add( 'PMFixTmpMCE', tinymce.plugins.PMFixTmpMCE );	
})();
