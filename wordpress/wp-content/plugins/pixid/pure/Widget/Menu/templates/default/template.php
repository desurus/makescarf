<?php
/**
 * Default Template
 * @param $menu_classes This variable contain all classes which provided via menu_class => settings argument.
 * */
?>
<ul class="<? echo implode(' ', $data->get('menu_classes')); ?>">
<? foreach($data->get('menu_items') as $menu_item): ?>
<li><a href="<? echo $menu_item->url; ?>" title="<? echo $menu_item->title; ?>"><? echo $menu_item->title; ?></a></li>
<? endforeach; ?>
</ul>
