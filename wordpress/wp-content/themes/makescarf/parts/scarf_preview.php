<html>
<head>
<? do_action('wp_enqueue_scripts'); ?>
<? wp_head(); ?>
<style>
.sceditor-container iframe {width: 98% !important;} 
</style>
</head>
<body>
<textarea id="preview"><?=$scarf->text; ?></textarea>
<script type="text/javascript">
jQuery(function($){
	
	init_sceditor('#preview', '<?=get_template_directory_uri();?>/sceditor/jquery.sceditor.default.min.css', {width: '90%', height: '400px', toolbar: ''});
	
});
</script>
</body>
</html>
<? exit(); ?>
