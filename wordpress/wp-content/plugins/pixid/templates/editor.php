<? $this->render('iframe-header.php'); ?>
<style>
.CodeMirror {
	direction: ltr !important;
}
</style>
<script type="text/javascript">
var editor_id = '<? echo $editor_id; ?>';
jQuery(function($){	
	//my editor tab
	//FIXME: This code need to be written as a plugin or library!
	
	SimplePHPBlocks.isDebug = true;

	var toolbar = $('.wp-editor-tabs');
	var codemirror_container = $('#codemirror-'+editor_id+'-editor-container');
	//Move codemirror block container just after the tmce editor container
	$(codemirror_container).insertAfter('#wp-'+editor_id+'-editor-container');	
	
	var codemirror_editor = CodeMirror.fromTextArea(document.getElementById('codemirror-textarea-'+editor_id), {
		lineNumbers: true,
		matchBrackets: true,
		mode: "application/x-httpd-php",
		indentUnit: 4,
		indentWithTabs: true,
		//RTL fixes
		
	});

	codemirror_editor.setSize('100%', 455);
	var myCodeButton = $('<button>Source</button>');
	$(myCodeButton).addClass('wp-switch-editor')
		.addClass('switch-source')
		.attr("type", "button")
		.data("wp-editor-id", editor_id);
	$(myCodeButton).on("click", function(e){
		e.preventDefault();
		if($(this).hasClass('active')) return false;
		$(toolbar).find('button').removeClass('active');
		$(this).addClass('active');
		//Update codemirror contents here
		var ed = tinyMCE.get(editor_id);
		var content = ed.getContent();	
		//$('#codemirror-textarea-'+editor_id).val(ed.getContent());	
		$('#wp-'+editor_id+'-editor-container').hide();
		$('#codemirror-'+editor_id+'-editor-container').show();
		codemirror_editor.getDoc().setValue(content);
		codemirror_editor.refresh();	
	});
	var tmceButton = $('<button>Visual</button>');
	$(tmceButton).addClass('wp-switch-editor')
		.attr('type', 'button')
		.data('wp-editor-id', editor_id);
	$(tmceButton).on("click", function(e){
		
		var ed = tinyMCE.get(editor_id);
		e.preventDefault();

		if($(this).hasClass('active')) return false;
		
		$(toolbar).find('button').removeClass('active');
		$(this).addClass('active');
		if(ed) {
			var content = codemirror_editor.getDoc().getValue();	
			ed.setContent(content);
		}
		$('#codemirror-'+editor_id+'-editor-container').hide();
		$('#wp-'+editor_id+'-editor-container').show();
	});

	$(toolbar).append(tmceButton);
	$(toolbar).append(myCodeButton);
	$(tmceButton).trigger('click');
	
	$('#pm_editor_save').click(function(){
		var code = "";
		if($(tmceButton).hasClass('active')) {
			code = tinyMCE.get(editor_id).getContent();
		} else {
			code = codemirror_editor.getDoc().getValue();
		}
		var file_in_editor = $('#pm_file_in_editor').val();
		$.ajax({
			url: ajax_url,
			method: 'post',
			data: { action: 'pm_save_code', 'pm_file_in_editor': file_in_editor, 'code': code },
			dataType: 'json',
			success: function(response) {
				if(parseInt(response.status) == 0) {
					window.parent.PM.closePMEditor();
					window.parent.location.reload(false);
				} else {
					alert(response.message);	
				}
			}
		});	
		return false;
	});
});
</script>
<div class="pm-editor-wide">
<? wp_editor($content, $editor_id, array( 'wpautop' => false, 'quicktags' => false, 'media_buttons' => true )); ?>
<div class="wp-editor-container" id="codemirror-<? echo $editor_id; ?>-editor-container">
<textarea style="width: 500px; heoght: 455px; margin: 5px; padding: 5px;" id="codemirror-textarea-<? echo $editor_id; ?>"><? echo $content; ?></textarea>
</div>
</div>
<div class="pm-snippet-info">
<div class="pm-snippet-filepath"><p><strong>File in editor:</strong> <? echo $file; ?></div>
</div>
<div class="pm-important-note">
<p>Current version of this editor is under active development. It <strong>can damage</strong> your file or post contents.</p>
<p>Please, be patient, and report any bugs to <a href="mailto:shell@hosted.pw?subject=PureEditorReport" title="Mail some info or bug.">shell@hosted.pw</a>.</p>
<p>Please, <strong>note</strong>, any of your file changes via this editor will be saved as a backup, and you can restore at any time the oldest file versions. All this actions available via <a href="#important-TODO">this link</a>.</p>
</div>
<?php
\_WP_Editors::enqueue_scripts();
print_footer_scripts();
\_WP_Editors::editor_js();
?>
<p>
<input type="hidden" name="pm_file_in_editor" value="<? echo $file_path_encoded; ?>" id="pm_file_in_editor" />
<input type="button" id="pm_editor_save" class="button submit default" value="<? _e('Save', 'wamt'); ?>" />&nbsp;
<input type="button" id="pm_editor_cancel" class="button reset default pm_dialog_close_button" value="<? _e('Cancel & Close', 'wamt'); ?>" />
</p>
<? $this->render('iframe-footer.php'); ?>
