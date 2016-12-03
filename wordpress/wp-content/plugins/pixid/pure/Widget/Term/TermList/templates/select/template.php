<? if(!$data->get('terms')) return; ?>
<?
$current = get_queried_object();
?>
<select name="<? echo $this->args()->get('name'); ?>" <? if($this->args()->get('select_id')): ?> id="<? echo $this->args()->get('select_id'); ?>" <? endif; ?> class="<? echo $this->args()->get('select_class'); ?>">
<? foreach($data->get('terms') as $term): ?>
<option <? if($current instanceof \WP_Term && $term->term_id == $current->term_id): ?>selected<? endif; ?> value="<? echo $term->term_id; ?>"><? echo $term->name; ?></option>
<? endforeach; ?>
</select>
