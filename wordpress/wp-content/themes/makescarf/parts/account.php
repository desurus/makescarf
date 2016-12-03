<?
if(!is_user_logged_in()):
?>
<div class="message notice">
<? echo $wp_errors->errors[0][0]; ?>
</div>
<? return; endif; ?>
<table class="scarf-cart" cellpadding="0" cellspacing="0" style="width: 100%;">
<tr class="head">
<td>Order #</td>
<td>Date</td>
<td>Total amount paid</td>
<td>Status of order</td>
<td>Shipping Address</td>
<td></td>
</tr>
<? foreach($orders as $order): ?>
<tr id="order_<? echo $order->ID; ?>">
<td><? echo $order->ID; ?></td>
<td><? echo $order->post_date; ?></td>
<td>$<? echo $order->total_amount_paid; ?></td>
<td><? echo $order->order_status; ?></td>
<td><? echo $order->shipping_address; ?></td>
<td style="padding: 10px 5px; width: 100px;"><a href="#" class="view-order-details" style="text-decoration: underline;" data-order-id="<? echo $order->ID; ?>">Order details</a>
</td>
</tr>
<? endforeach; ?>
</table>
<div style="margin-top: 20px;">
<? foreach($orders as $order): ?>
<div style="display: none;" class="order-details" id="order_details_<? echo $order->ID; ?>">
<? $items = $order->scarvs; ?>
<table class="scarf-cart" cellpadding="0" cellspacing="0">
<tr class="head">
<td>Preview</td>
<td>Color</td>
<td>Text color</td>
<td>Scarf Style</td>
<td>Count</td>
<td>Price</td>
<td></td>
</tr>
<? foreach($items as $item): ?>
<tr id="item_<? echo $item->ID; ?>">
<td><a href="/scarf-preview?sid=<?=$item->ID;?>" class="fancybox iframe preview" style="color: <? echo $item->text_color; ?>; background-color: <? echo $item->background_color; ?>; font-family: <? echo $item->font_variant; ?>; padding: 5px; font-size: 14px; line-height: 16px; ">Your text here.</div></td>
<td><? echo $item->background_color; ?></td>
<td><? echo $item->text_color; ?></td>
<td><? echo $item->scarf_style; ?> <? if($item->scarf_style == 'infinity'): ?><b>+$5</b><? endif; ?></td>
<td><? echo $item->items_count; ?></td>
<td class="price"><span id="price_<? echo $item->ID; ?>" class="item-price-span"><? echo $item->price; ?></span>$</td>
<!-- td><a href="#" class="remove-scarf" data-scarf-id="<? echo $item->ID; ?>">Remove scarf</a></td -->
<td><a class="readd-scarf" href="<?=get_permalink(CART_PAGE_ID).'?add='.$item->ID; ?>">Add to cart</a></td>
</tr>
<? endforeach; ?>
</table>
<div style="display: none;">
<? foreach($items as $item) :
$font_size = '14px';
$line_height = '16px';
if('ZapfinoForteL' == $item->font_variant) {
	$font_size = '22px';
	$line_height = '24px';
}
?>

<? endforeach; ?>
</div>
</div>
<? endforeach; ?>
</div>
	<script type="text/javascript">
	
	jQuery(function($){
		$('.view-order-details').click(function(){
			$('.order-details').hide();
			var id = $(this).data('order-id');
			$('#order_details_'+id).fadeIn();
			return false;
		});
		$('.remove-scarf').click(function(){
			var id = $(this).data('scarf-id');
			if(confirm("Are you really want to remove this item from cart?")) { 
				$.ajax({
					url: scarf_ajax_url,
					data: { action: 'remove_scarf', 'sid': $(this).data('scarf-id') },
					complete: function() {
						$('#item_'+id).hide();
					}
				})
			}
				return false;
		});
	})
</script>
