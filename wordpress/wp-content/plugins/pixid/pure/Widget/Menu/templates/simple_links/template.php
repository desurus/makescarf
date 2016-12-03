<?
$menu = array();
?>
<? foreach($menu_items as $menu_item): ?>
<? 
	$menu[] = "<a href=\"{$menu_item->url}\" title=\"{$menu_item->title}\">{$menu_item->title}</a>"; 
?>
<? endforeach; ?>
<? echo implode($params->get('items_separator'), $menu); ?>
