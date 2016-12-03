<?php
/**
 * Default Template
 * @param $menu_classes This variable contain all classes which provided via menu_class => settings argument.
 * */
$active_class = $this->args()->get('active_class', 'active');
$menu_classes = $data->get('menu_classes');
$menu_classes[] = $this->args()->get('list_class', '');
?>
<ul class="<? echo implode(' ', $menu_classes); ?>">
<? foreach($data->get('menu_items') as $menu_item): ?>
<li class="<? if($menu_item->current) echo " {$active_class}"; ?>"><a href="<? echo $menu_item->url; ?>" title="<? echo $menu_item->title; ?>"><? echo $menu_item->title; ?></a></li>
<? endforeach; ?>
</ul>
