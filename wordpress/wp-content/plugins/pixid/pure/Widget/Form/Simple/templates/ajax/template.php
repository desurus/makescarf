<?
$form = $data->get('form');
$this->include_js('jquery');
?>
<script type="text/javascript">
jQuery(function($){
	$('#<? echo $form->get_id(); ?>').submit(function(){
		var form = $(this);
		var data = $(this).serialize();
		$.ajax({
			method: 'post',
			url: '<? echo $this->ajax_url('send_form'); ?>',
			dataType: 'json',
			data: data,
			success: function(response) {
				if(parseInt(response.code) == 0) {
					alert(response.message);
					$(form).find('input').each(function(i, el){
						$(el).val('');
					});
				} else {
					alert(response.message);
					console.log(response);
				}
			}
		});
		return false;
	});
});
</script>
<? echo $form->render_header(); ?>
<? foreach($form->get_elements() as $element): ?>	
				<p>
<? echo $element->render_input(); ?>
<label for="<? echo $element->get_id(); ?>"><? echo $element->get_option('label', $element->get_option('title')); ?></label>
					</p>
<? endforeach; ?>
<input class="btn btn-size-1" type="submit" name="button" value="<? echo $this->args()->get('submit_title', __('Send', 'wamt')); ?>">
<? echo $form->render_footer(); ?>
