<?php
/**
 * @Template data: 
 * $settings_container A fully initialized settings container with all available settings for this widget and it's current data.
 * $widget	Just a initialized widget object.
 * @author Shell
 * @version 0.1
 * @
 * */
?>
<div class="pm_widget_settings_form">
<p class="pm_widget_settings_description"><strong>Widget</strong>: <? $this->Widget()->display_name($widget_name); ?></p>
<form class="" id="" method="post" action="">
<? foreach($settings_container->get_items() as $item): ?>
<p><label for="<? echo $item->get_id(); ?>"><? echo $item->get_title(); ?>:</label><? echo $item->render(); ?>
<? if($item->get_description()): ?>
	<span class="widget_settings_item_description"><? echo $item->get_description(); ?></span>
<? endif; ?>
</p>
<? endforeach; ?>
<p class="button-holder">
<? $this->render("common/form/submit_button.php"); ?>
<? $this->render("common/form/close_button.php"); ?>
</p>
</form>
</div>

