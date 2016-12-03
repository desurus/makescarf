<? if(empty($items)): ?>
<div class="notice">
<p class="error">
Your cart is empty
</p>
</div>
<? return; endif; ?>
<script type="text/javascript">
jQuery(function($){
	$('.item-count-change').click(function(){
		var item_id = $(this).data('item-id');
		var count = $('#item_'+item_id+'_count').val();
		if($(this).hasClass('decrease')) {
			count--;
		} else {
			count++;
		}

		$('#item_'+item_id+'_count').val(count);
		$('#item_'+item_id+'_count').trigger('change');
		return false;
	});
	$('.item-count').change(function(){
		var count = $(this).val();
		var item_id = $(this).data('item-id');
		if((count <= 0 && confirm("Are you really want to delete this item from cart?")) || count > 0) {
			$.ajax({
				url: scarf_ajax_url,
					data: { action: 'modify_count', 'item_id': item_id, 'count': count },
					dataType: 'json',
					success: function(data) {
						if(data.action == 'remove') {
							$('#item_'+data.id).fadeOut().remove();
						}
						$('#total-amount').text(data.total_price);
					}	
			});
		}
	});
	$('#use_coupon_code').click(function(){
		var coupon_code = $('#coupon_code').val();
		if(coupon_code == "") {
			alert("You must enter a coupon code.");
			return false;
		}
		$.ajax({
			url: scarf_ajax_url,
			data: { action: 'use_coupon', coupon_code: coupon_code },
			method: 'get',
			dataType: 'json',
			success: function(response) {
				if(response.code == 0) {
					$('.item-count').trigger('change');
				} else {
					//TODO:
				}
			}
		})
		return false;
	})
});
</script>
	<form action="<? echo get_permalink(ORDER_PAGE_ID); ?>" method="post">
<table class="scarf-cart" cellpadding="0" cellspacing="0">
<tr class="head">
<td>Preview</td>
<td>Color</td>
<td>Text color</td>
<td>Scarf Style</td>
<td>Count</td>
<td>Price</td>
</tr>
<? foreach($items as $item): ?>
<tr id="item_<? echo $item->ID; ?>">
<td><div class="preview" style="color: <? echo $item->text_color; ?>; background-color: <? echo $item->background_color; ?>; font-family: <? echo $item->font_variant; ?>; padding: 5px; font-size: 14px; line-height: 16px; ">Your text here.</div></td>
<td><? echo $item->background_color; ?></td>
<td><? echo $item->text_color; ?></td>
<td><? echo $this->FetchTemplatePart('stylesdropdown.php', array('item' => $item)); ?></td>

<td><a class="item-count-change decrease" data-item-id="<? echo $item->ID; ?>" href="#">-</a><input class="item-count" data-item-id="<? echo $item->ID; ?>" type="number" id="item_<? echo $item->ID; ?>_count" style="width: 30px;" name="items_count" value="<? echo $item->items_count; ?>"/><a href="#" class="item-count-change" data-item-id="<? echo $item->ID; ?>">+</a></td>
<td class="price"><span id="price_<? echo $item->ID; ?>" class="item-price-span"><? echo $item->price; ?></span>$</td>
</tr>
<? endforeach; ?>
</table>

<div class="total-amount">
<p>Total amount: <span id="total-amount"><? echo @$total_amount; ?></span>$</p>
</div>

<div class="wrapBtn">
<? if(MakeScarf_Theme::DiscountEnabled()): 
$current_coupon = MakeScarf_Theme::GetSessionCoupon();
$coupon_code = "";
if(!empty($current_coupon)) $coupon_code = $current_coupon->coupon_code;
?>
<p>
<input type="text" name="coupon_code" id="coupon_code" value="<? echo $coupon_code; ?>" placeholder="Enter a discount coupon code..." class="input-wide" />
</p>
<button id="use_coupon_code" class="btn">Use coupon code</button>
<? endif; ?>
<button onclick="window.location = '<? echo get_permalink(MAKE_SCARF_PAGE_ID); ?>'; return false;" class="btn">Add another scarf</button>
					<button type="submit" class="btn">Proceed to Shipping info</button>
					</div>
</form>
