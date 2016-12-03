<?
/**
 * This widget template may been used to display a more or less pretty select inputs of dropdown terms lists. Which binded window.location redirect on select changes.
 * It may be very usefull in some projects.
 * */
//This template requires jQuery is loaded.
$element_uid = $this->get_uid(); 
?>
<script type="text/javascript">
jQuery(function($){
	var this_uid = '<? echo $element_uid; ?>';
	var selects = $('select');
	if(selects.length == 0) return;
	$(selects).each(function(i, select){
		if($(select).data('element_uid') && $(select).data('element_uid') == this_uid) {
			$(select).change(function(){
				var selected = $(this).find('option:selected');
				if($(selected).data('location')) {
					window.location = $(selected).data('location');
				}
			});	
		}
	});
});
</script>
<? if(!$data->get('terms')) return; ?>
<?
$current = get_queried_object();
if(is_object($current) && ($current instanceof \WP_Term)) {
	$current_term_id = $current->term_id;
} else {
	$current_term_id = $this->args()->get('selected_term_id', 0);
}
?>
<select data-element_uid="<? echo $element_uid; ?>" name="<? echo $this->args()->get('name'); ?>" <? if($this->args()->get('select_id')): ?> id="<? echo $this->args()->get('select_id'); ?>" <? endif; ?> class="<? echo $this->args()->get('select_class'); ?>">
<? foreach($data->get('terms') as $term): ?>
<option <? if($current_term_id == $term->term_id): ?>selected<? endif; ?> value="<? echo $term->term_id; ?>" data-location="<? echo get_term_link($term); ?>"><? echo $term->name; ?></option>
<? endforeach; ?>
</select>
