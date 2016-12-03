<h2>Shipment settings</h2>
<? if(!empty($saved)): ?><div class="message updated"><p>Settings saved.</p></div><? endif; ?>
<? if(!empty($error_msg)): ?><div class="message error"><p><? echo $error_msg; ?></p></div><? endif; ?>
<div class="wrap">
<form method="post" action="">
<p><label for="settings_content"></label></p>
<p><textarea id="settings_content" style="width: 600px; height: 500px; " name="settings_content"><? echo $settings_content; ?></textarea></p>
<p><input type="submit" name="save" value="Save settings" class="button default" /></p>
</form>
</div>
