<? if(!$data->get('terms')) return; ?>
<ul <? if($this->args()->get('list_id')): ?> id="<? echo $this->args()->get('list_id'); ?>" <? endif; ?> class="<? echo $this->args()->get('list_class'); ?>">
<? foreach($data->get('terms') as $term): ?>
	<li <? if($term->selected): ?>class="selected"<? endif; ?>><a href="<? echo get_term_link($term); ?>"><? echo $term->name; ?></a></li>
<? endforeach; ?>
</ul>
