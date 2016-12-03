/**
 * Plugin name: PMFixNoElements
 * Description: http://track.hosted.pw/issues/76
 * This bug described in the tracker, the link above. 
 * @version 0.2
 * @author Shell
 * */

(function() {	
	tinymce.create('tinymce.plugins.PMFixNoElements', {
		init: function(editor) {
			editor.on("BeforeSetContent", function(e){
				//@version 0.2: We need to execute this hook only while all editor content updated!	
				if(e.selection && !e.initial) return e;	
				var content = e.content;	
				e.content = "<div class='pm_bug_fix'>"+e.content+"</div>";
				return e;
			});
			editor.on("GetContent", function(e){
				var content = e.content;
				/*var node = jQuery(e.content);
				if(jQuery(node).hasClass('pm_bug_fix')) { 
					e.content = jQuery(node).html();	
				}*/
				content = content.replace(/(<div class="pm_bug_fix">)(.*?)(<\/div>)/, "$2");
				content = content.replace(/(<div class='pm_bug_fix'>)(.*?)(<\/div>)/, "$2");
				e.content = content;
				return e;
			});
		}
	});
	tinymce.PluginManager.add( 'PMFixNoElements', tinymce.plugins.PMFixNoElements );	
})();
