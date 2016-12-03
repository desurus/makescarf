<script type="text/javascript">
jQuery(function($){
	$('#<? echo $this->args()->get('id', 'color'); ?>').wpColorPicker();
})
</script>
<input 
name="<? echo $this->args()->get('name')?>" 
type="text" 
value="<? echo $this->args()->get('value'); ?>" 
class="" 
data-default-color="<? echo $this->args()->get('default_value'); ?>" 
id="<? echo $this->args()->get('id', 'color'); ?>" />
