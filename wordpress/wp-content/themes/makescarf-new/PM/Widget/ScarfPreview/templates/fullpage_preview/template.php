<html>
<head>
<title>Full scarf preview</title>
<link rel="stylesheet" href="<? echo get_template_directory_uri(); ?>/constructor/fonts.css" />
<? 
$settings = \NMakeScarf_Theme::instance()->get_settings(); 
$ident = $settings['constructor_indent'];
?>
<style>
html, body { 
  height:100%;
}
.layout {
	padding: <? echo $ident; ?> <? echo $ident; ?> 0 <? echo $ident; ?>;
	overflow: hidden;
	/* zoom: <? echo $settings['fullscreen_zoom']; ?>;*/
	border-bottom: <? echo $ident; ?> solid white;
	box-sizing: border-box;
}
.layout_horizontal {
	width: <? echo $settings['constructor_width']; ?>;
	height: <? echo $settings['constructor_height']; ?>;  
}
.layout_vertical {
	width: <? echo $settings['constructor_height']; ?>;
	height: <? echo $settings['constructor_width']; ?>;
}
</style>
</head>
<? 
// $data->get('scarf') is instance of \WC_Product
$scarf = $data->get('scarf');
$layout = strtolower($scarf->get_attribute('layout'));
?>
<body>
<div class="layout layout_<? echo $layout; ?>">
<? echo $scarf->post->post_content; ?>
</div>
</body>
</html>
<? exit(); ?>
