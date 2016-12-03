<?
if(empty($states))
	return;
extract($args);
?>
<select name="<? echo $name; ?>" id="<? echo $id; ?>" class="<? echo $class; ?>"<? if($required) echo ' required="required"'; ?>>
<? foreach($states as $state): ?>
<option value="<? echo $state->post_title; ?>" <? if($selected == $state->post_title) echo ' selected'; ?>><? echo $state->post_title; ?></option>
<? endforeach; ?>
</select>
