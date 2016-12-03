<?php
/**
 * Simple template 
 * This basic menu template created just for bakcompatibility with widget \Menu\Simple, which is deprecated now from version 0.0.20 
 * */
$active_class = $this->args()->get('active_class', 'current-menu-item');
?>
	<div class="<? echo $this->args()->get('container_class'); ?>">
<ul class="<? echo $this->args()->get('menu_class'); ?>">
<? foreach($data->get('menu_items') as $menu_item): ?>
<li class="<? if($menu_item->current) echo " {$active_class}"; ?>"><a href="<? echo $menu_item->url; ?>" title="<? echo $menu_item->title; ?>"><? echo $menu_item->title; ?></a></li>
<? endforeach; ?>
</ul>
</div>
