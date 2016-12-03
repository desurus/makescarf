<? if(!$data->get('terms')) return; ?>
<? foreach($data->get('terms') as $term): ?>
	<a href="#"><? echo $term->name; ?></a>
<? endforeach; ?>
