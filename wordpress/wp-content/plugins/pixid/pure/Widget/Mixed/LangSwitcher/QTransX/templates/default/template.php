<?
$languages = $data->get('languages');
if(empty($languages)) return;
$classes = array(
	'he' => 'jewish',
	'ru' => 'rus',
	'en' => 'eng'
);
?>
<ul class="lang-list">
<? foreach($languages as $lang): 
$class = @$classes[$lang['code']];
if(empty($class)) $class = $lang['code'];
?>		
						<li>
						<a href="<? echo $lang['url']; ?>" class="<? echo $class; ?>"></a>
								</li>
<? endforeach; ?>
</ul>
