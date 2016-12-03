
<p class="stepsTitle" style="text-align: left;">Full information:</p>
<div class="stepsBox">
<p class="priceTitle">
Merchandise Subtotal <span><i>$</i><?=round($products_price, 2);?></span><? if(!empty($tax)): ?><br /><? echo $state->post_title; ?> tax <span><i>$</i><?=$tax_value?></span><? endif; ?><br />
Shipping cost <span><i>$</i><?=$delivery; ?></span> (<? echo @$delivery_title;?>)<br />
Total <span><i>$</i><?=round($total_price, 2); ?></span>
</p>
</div>
<p>


<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
<input type="hidden" name="cmd" value="_xclick">
<input type="hidden" name="business" value="<?=$this->options->GetOption('paypal_account'); ?>">
<input type="hidden" name="lc" value="US">
<input type="hidden" name="item_name" value="<?=$order->post_title?>">
<input type="hidden" name="item_number" value="<?=$order->ID; ?>">
<input type="hidden" name="amount" value="<?=$products_price; ?>">
<input type="hidden" name="currency_code" value="USD">
<input type="hidden" name="button_subtype" value="services">
<input type="hidden" name="no_note" value="0">
<? if(!empty($tax)): ?>
<input type="hidden" name="tax_rate" value="<?=$tax; ?>">
<? endif; ?>
<input type="hidden" name="shipping" value="<?=$delivery; ?>">
<input type="hidden" name="bn" value="PP-BuyNowBF:btn_buynowCC_LG.gif:NonHostedGuest">
<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_buynowCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
<img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
</form>
</p>
