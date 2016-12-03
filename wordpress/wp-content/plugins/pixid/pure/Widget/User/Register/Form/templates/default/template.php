<?php
$form_data = $data->get('form_data');
?>
<div class="pm_register_form_wrap">

<? if($data->get('errors')): ?>
<? foreach($data->get('errors') as $error): ?>
<div class="alert warning"><? echo $error; ?></div>
<? endforeach; ?>
<? endif; ?>

<form method="post" action="" name="pm_register_form">
<table>
<tr>
<td>Email:</td>
<td><input type="text" name="user_email" value="<? echo $form_data['user_email']; ?>" /></td>
</tr>
<tr>
<td colspan="2"><input type="submit" name="do_register" value="<? _e('Register me'); ?>"></td>
</tr>
<tr>
<td colspan="2">
<? 
$links = array();
if($this->args()->get('login_url')) {
	$url = $this->args()->get('login_url', '/user/login');
	$links[] = "<a href=\"{$url}\">Login</a>";
}
if($this->args()->get('lost_password_url')) {
	$url = $this->args()->get('lost_password_url');
	$links[] = "<a href=\"{$url}\">Forgot password?</a>";
}
?>
<? if(!empty($links)): ?>
<? echo implode(' | ', $links); ?>
<? endif; ?>
</td>
</tr>
</table>
<input type="hidden" name="redirect_url" value="<? echo $data->get('redirect_url'); ?>" />
</form>
</div>
