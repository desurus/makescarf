<div class="pm_login_form">
<? if($data->get('errors')): ?>
<? foreach($data->get('errors') as $error): ?>
<p class="login_error"><? echo $error; ?></p>
<? endforeach; ?>
<? endif; ?>
<form name="login_form" action="" method="post">
<table>
<tr>
<td><label for="user_login"><? _e('Login'); ?></label></td>
<td><input type="text" name="user_login" value="" placeholder="<? _e("Login"); ?>" id="user_login" /></td>
</tr>
<tr>
<td><label for="user_password"><? _e("Password"); ?></label></td>
<td><input type="password" name="user_password" id="user_password" /></td>
</tr>
<tr>
<td colspan="2"><input type="submit" name="login" value="<? _e('Login'); ?>" /></td>
</tr>
<input type="hidden" name="redirect_url" value="<? echo $data->get('redirect_url'); ?>" />
</table>
</form>
</div>
<? if($this->args()->get('register_url')): ?>
<p><a href="<? echo $this->args()->get('register_url'); ?>"><? _e('Register'); ?></a></p>
<? endif; ?>
