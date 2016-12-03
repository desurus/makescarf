<style>
.stForm p{
	padding-bottom: 15px;
	overflow: hidden;
	font-size: 16px;
}
.stForm p.stFormTitle{
	font: 28px/22px Times, Georgea, serif;
	padding: 10px 0 20px;
}
.stForm p.stMiniTitle{
	font: 22px/18px Times, Georgea, serif;
	color: #8b9aa7;
}
.stForm p span{
	width: 210px;
	float: left;
}
.stForm p span.stSmallInp{width: 80px;}
.stForm p span.stSmallInp input[type="text"]{width: 70px;}
.stForm p span.hitsShow{
	clear: left;
	padding: 4px 0 0;
}
.stForm p span.hitsShow input{
	position: relative;
	top: -2px;
}
.stForm p span.hitsShow a{
	text-decoration: none;
	color: #1481cc;
}
.stForm p span.hitsShow a:hover{text-decoration: underline;}
.stForm p input[type="text"]{
	display: block;
	margin: 5px 0 0;
	width: 190px;
	padding: 0 5px;
	height: 28px;
	border: 1px solid #b3b3b3;
}
.stForm p input[type="submit"]{
	position: relative;
	background: #009ae6;
	-moz-border-radius: 5px;
	-webkit-border-radius: 5px;
	border-radius: 5px;
	margin: 10px 0;
	padding: 10px 20px;
	border: 1px solid #0576ae;
	color: #fff;
	font-weight: bold;
	text-shadow: 0 1px #0576ae;
	font-size: 18px;
	-webkit-box-shadow: -4px 0 2px -2px #d1d1d1; 
	-moz-box-shadow: -4px 0 2px -2px #d1d1d1; 
	box-shadow: 0 4px 2px -2px #d1d1d1;
}
.stForm select { display: block; }
.delivery-select-block {
	display: none;
}
    </style>
<script type="text/javascript">
jQuery(function($){
	$('#user_country').change(function(){
		if($(this).val() == 'USA') {
			$('#state').fadeIn();
			$('#user_state').attr('required', 'required');
			$('#user_province_block').fadeOut();
		}
		else {
			$('#user_state').removeAttr('required');
			$('#state').fadeOut();
			$('#user_province_block').fadeIn();	
		}
	});
	$('#user_country').change(function(){
		var country = $(this).val();
		var block = $('#country-'+country);
		$('.delivery-select-block').hide();
		if(block.length > 0) {
			$(block).show();
			$(block).find('.delivery-radio:first').trigger('click');
		}
		else {
			$('#country-Default').fadeIn();
			$('#country-Default').find('.delivery-radio:first').trigger('click');
		}
	});
	$('.delivery-radio').change(function(){
		$('#delivery_method_value').val($(this).val());
	});
	$('#user_country').trigger('change');
});
</script>
<? if(!empty($wp_errors->errors)): foreach($wp_errors->errors as $wp_error): ?>
<div class="message notice">
<p><? echo $wp_error[0]; ?></p>
</div>
<? endforeach; endif; ?>
<? if(empty($scarvs)): ?>
<? return; endif; ?>
<form class="stForm" action="" method="post">
					<p class="stFormTitle">Shipping Information</p>
					<p class="stMiniTitle">Please, specify your contact information.</p>
					<p>
					<span><label for="first_name">First Name <input id="first_name" type="text" value="<? echo @$data['first_name']; ?>" required="required" name="first_name"/></label></span>
						<label for="last_name">Last Name <input id="last_name" type="text" value="<? echo @$data['last_name']; ?>" required="required" name="last_name"/></label></span>
					</p>
<p>
					<span><label for="user_email">Contact Email <input id="user_email" type="text" value="<? echo @$data['user_email']; ?>" required="required" name="user_email"/></label></span>
						<label for="user_phone">Contact Phone <input id="user_phone" type="text" value="<? echo @$data['user_phone']; ?>" required="required" name="user_phone"/></label></span>
					</p>
<p class="stMiniTitle">Please, specify shipping information.</p>
<? if(empty($data['user_country'])) $data['user_country'] = 'USA'; ?>
<p><span><label for="user_country">Country <? MakeScarf_Theme::Me()->CountryDropdown(array('selected' => @$data['user_country'])); ?></label>
</p>
<p id="user_province_block"><span><label for="province">Province / Territory <input type="text" name="user_province" id="user_province" value="<? echo @$data['user_province']; ?>"></label></span></p>
<p id="state" style="display: none;"><span><label for="user_state">State: <? MakeScarf_Theme::Me()->StatesDropdown(); ?></label></span></p>
			
<p>	
<span><label for="user_city">City<input type="text" name="user_city" value="<? echo @$data['user_city']; ?>" id="user_city" required="required" /></label></span>
						<span class="stSmallInp"><label for="user_zip_code">Zip code
						<input type="text" name="user_zip_code" value="<? echo @$data['user_zip_code']; ?>" id="user_zip_code" required="required"/></span></label></p>
<p>
<span><label for="user_address">Street address <textarea required="required" name="user_address" id="user_address"><? echo @$data['user_address']; ?></textarea></label></span>
</p>			

<p class="stMiniTitle">Please, specify shipping method.</p>

<p> 
<? 
foreach($delivery_conf as $country => $info) :
?>
<div id="country-<? echo $country; ?>" class="delivery-select-block">
<? foreach($info as $hash => $params): ?>
<p>
<input type="radio" class="delivery-radio" name="delivery_country_<? echo $country; ?>" value="<? echo $hash; ?>">&nbsp;<? echo $params['title']; ?> (<b>$<?echo $params['coast']; ?></b>)
</p>
<? endforeach; ?>
</div>
<? endforeach; ?>
</p>
<? 
$coupon = MakeScarf_Theme::GetSessionCoupon();
if(!empty($coupon)): ?>
<input type="hidden" name="coupon_id" value="<? echo $coupon->ID; ?>" />
<? endif; ?>
<? if(!empty($data['ID']) && $data['post_status'] == 'draft'): ?>
<input type="hidden" name="draft_order_id" value="<? echo $data['ID']; ?>" />
<? endif; ?>
<input type="hidden" name="delivery_method_value" value="" id="delivery_method_value" />
					<div class="wrapBtn"><input type="submit" class="btn" value="Procees to Payment" /></div>
<input type="hidden" name="scarf_action" value="process_order" />
</form>
