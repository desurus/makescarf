<?
if(empty($countries))
	return;
extract($args);
?>
<select name="<? echo $name; ?>" id="<? echo $id; ?>" class="<? echo $class; ?>"<? if($required) echo ' required="required"'; ?>>
<? foreach($countries as $country): ?>
<option value="<? echo $country->post_title; ?>" <? if($selected == $country->post_title) echo ' selected'; ?>><? echo $country->post_title; ?></option>
<? endforeach; ?>
</select>
