<?php _wp_admin_html_begin(); ?>
<title><?php ?></title>
<meta name="viewport" content="width=device-width,initial-scale=1.0">
<? 
global $hook_suffix;
wp_enqueue_style('pm-styles', PM()->plugin_dir_url() . '/css/pm-styles.css');
wp_enqueue_script('pm-iframe', PM()->plugin_dir_url() . '/js/pm-iframe.js', array('jquery'));
do_action( 'admin_enqueue_scripts', $hook_suffix );
do_action( 'admin_print_styles' );
do_action( "admin_print_scripts-$hook_suffix" );
do_action( 'admin_print_scripts' );
do_action( "admin_head-$hook_suffix" );
do_action( 'admin_head' );
?>
<style>
.wp-editor-tabs button.active {
	background: #f5f5f5;
    color: #555;
    border-bottom-color: #f5f5f5;
}
.pm-snippet-info {
	margin-top: 5px;
}
.pm-important-note p {
	margin: 3px;	
}
.pm-important-note {
	padding: 3px;
	border: 1px solid gray;
	background: white;
}
body {
	padding: 10px;
}
</style>
<script type="text/javascript">
var ajax_url = '<? echo admin_url('admin-ajax.php'); ?>';
var ajaxurl = '<? echo admin_url('admin-ajax.php'); ?>';
jQuery(document).ready(function(){
	
});
</script>
</head>
<body class="blue">
<div class="pm_iframe_wrap">
<div class="pm_iframe_header">
<?
	if(empty($title)) {
	}
?>
<? if(!empty($title)): ?>
<span class="popup-caption"><? echo $title; ?></span>
<? endif; ?>
</div>
