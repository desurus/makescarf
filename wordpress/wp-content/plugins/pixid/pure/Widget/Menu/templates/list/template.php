<?php
/**
 * Default Template
 * @param $menu_classes This variable contain all classes which provided via menu_class => settings argument.
 * */
$active_class = $this->args()->get('active_class', 'active');
$menu_classes = $data->get('menu_classes');
$menu_classes[] = $this->args()->get('list_class', '');
$prev_level = 0;
$opened = false;
?>
<ul class="<? echo implode(' ', $menu_classes); ?>">
<? foreach($data->get('menu_items') as $menu_item): ?>
<? if($prev_level && $menu_item->depth < $prev_level): $opened = false; ?>
<? echo str_repeat('</ul></li>', ($prev_level - $menu_item->depth)); ?>
<? endif; ?>
<li class="<? if($this->args()->get('item_class')) echo $this->args()->get('item_class'); ?><? if($menu_item->current) echo " {$active_class}"; ?>">
<a href="<? echo $menu_item->url; ?>" title="<? echo $menu_item->title; ?>"><? echo $menu_item->title; ?></a>
<? if($menu_item->is_parent): $opened = true; ?>
<ul class="nav-sublist">
<? endif; ?>
<? if(!$menu_item->is_parent): ?>
</li>
<? endif; ?>
<? $prev_level = $menu_item->depth; endforeach; ?>
<? if($opened) echo "</ul></li>"; ?>
</ul>
