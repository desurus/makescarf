<?
if(empty($item)) return "Incorrectt usign";
$styles = array(
	'straight' => 'Straight',
	'infinity' => 'Infinity'
);
?>
<select class="scarf-style" data-scarf-id="<?=$item->ID;?>" name="scarf[style]" id="scarf_style_<?=$item->ID?>">
<? foreach($styles as $k => $style): ?>
<option value="<?=$k;?>" <? if($k == $item->scarf_style) echo "selected "?>><?=$style?></option>
<? endforeach; ?>
</select>
	<b class="inf-label"<? if($item->scarf_style == 'straight'):?> style="display: none;"<?endif?>>+$5</b>
