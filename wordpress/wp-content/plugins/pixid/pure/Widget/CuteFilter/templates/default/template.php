<div class="catalog-filter" id="catalog-filter-main">
                    <div>Выбор мебели по типу:</div>
		    <div>
<? 
$i = 0; 
foreach($properties['pa_furn-type']['values'] as $value): 
	$i++; 
	$url = \PureLib\Helper\Url::current([ "cf_filter" => [ "pa_furn-type" => $value['value'] ], 'use_filter' => 'Y' ]) . '#catalog-filter-main';
?>
<a href="<? echo $url; ?>" class="<? if($value['selected']) echo " active"; ?>"><? echo $value['title']; ?></a>
<? 
if($i<count($properties['pa_furn-type']['values'])): ?>			
<span>|</span>
<? endif; ?>
<? endforeach; ?>
		    </div>
<div class="filter-title">Выбор по цене:</div>
<div style="margin-top: 5px;">

<form method="get" action="<? echo $form_action_url; ?>">
<? foreach($current_filter as $name => $value): ?>
<input type="hidden" name="cf_filter[<? echo $name; ?>]" value="<? echo $value; ?>" />
<? endforeach; ?>
			<div class="price_slider_wrapper">
				<div class="price_slider" style="display:none;"></div>
				<div class="price_slider_amount">
				<input type="text" id="min_price" name="min_price" value="<? echo @$filter['min_price']; ?>" data-min="<? echo $min_price; ?>" placeholder="Минимальная цена">
					<input type="text" id="max_price" name="max_price" value="<? echo @$filter['max_price']; ?>" data-max="<? echo $max_price; ?>" placeholder="Максимальная цена">
					<button type="submit" class="button">Фильтровать</button>
					<div class="price_label" style="display:none;">
						Цена: <span class="from"></span> — <span class="to"></span>
					</div>
					
					<div class="clear"></div>
				</div>
			</div>
</form>

</div>
		</div>
