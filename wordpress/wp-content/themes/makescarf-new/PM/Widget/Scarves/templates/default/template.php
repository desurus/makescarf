<? if(!$data->get('posts')): ?>
<div class="alert danger"><a href="/make-your-scarf">Create your first scarf</a>. No scarfs found in your profile!</div>
<? endif; ?>
<? $this->include_js('jquery'); ?>
<script type="text/javascript">
jQuery(function($){
	$('.delete_scarf_button').click(function(){
		if(!confirm("Are you really want to delete this scarf from your account history?")) {
			return false;
		}
		var scarf_id = $(this).data('scarf_id');
		$.ajax({
			url: '<? echo $this->ajax_url('delete_scarf'); ?>',
			data: { scarf_id: scarf_id },
			dataType: 'json',
			method: 'get',
			success: function(response) {
				if(0 === parseInt(response.status)) {
					$('#tr_scarf_'+scarf_id).fadeIn().remove();
				} else {
					alert(response.message);
					console.log(response);
				}	
			}
		})
		return false;
	});
});
</script>

<table class="table table-bordered t-history">
<thead>
<tr>
<td>Font preview</td>
<td>Text color</td>
<td>Scarf style</td>
<td>Price</td>
</tr>
</thead>
<? foreach($data->get('posts') as $post): 
$product = PM()->Woo()
		->get_product_by_id($post->ID);
?>
<tr id="tr_scarf_<? echo $post->ID; ?>">
<td>
<? PM()->display_widget("MakeScarf\Widget\ScarfPreview\Widget", array("post_id" => $post->ID, "template" => "new", "width" => '190px', "height" => '64px')); ?>
</td>
<td>
<span class="color-box" style="background-color: #<? echo $product->get_attribute('font_color'); ?>"></span>#<? echo $product->get_attribute('font_color'); ?>
</td>
<td>
<? echo $product->get_attribute('style'); ?>
</td>
<td>
$<? echo $product->get_price(); ?>
</td>
</tr>
<? endforeach; ?>
</table>
