<script>
jQuery(function($){
	$('#generate_scarf_file').click(function(){
		$.ajax({
			method: 'get',
			url: '<? echo $this->ajax_url('create_file'); ?>',
			dataType: 'json',
			success: function(response) {
				if(parseInt(response.code) == 0) {
					if(response.data.html) {
						$('#scarf_attachment').replaceWith(response.data.html);
					}
				} else {
					alert(response.message);
					console.log(response);
				}
			}
		});
	});
});
</script>
<p>
<input type="button" name="generate_scarf_file" id="generate_scarf_file" value="Create file" />
</p>
