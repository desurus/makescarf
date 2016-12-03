<style>
.scarvs-preview li {
	list-style: none;
	width: 50px;
	padding: 10px;
}
.scarvs-preview li a {
	display: block;
}
</style>
<ul class="scarv-preview">
<? foreach($scarvs as $scarf): 
$font_size = '14px';
$line_height = '16px';
if($scarf->font_variant == 'ZapfinoForteL')
{
	$font_size = '22px;';
	$line_height = '24px';
}
?>
	<li><a target="_blank" href="<? echo admin_url('post.php?post='.$scarf->ID.'&action=edit'); ?>" style="background-color: <?=$scarf->background_color; ?>; color: <? echo $scarf->text_color; ?>; font-family: <? echo $scarf->font_variant; ?>; font-size: <?=$font_size;?>; line-height: <?=$line_height; ?>; padding: 5px;">Sample text</a></li>
<? endforeach; ?>
</ul>
