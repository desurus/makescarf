<? 
/**
This widget template uses a basic Bootstrap markup.
*/
?>
<div class="container">
<div class="row">
<? if($data->get('errors')): ?>
<? foreach($data->get('errors') as $error): ?>
<div class="alert danger"><? echo $error; ?></div>
<? endforeach; ?>
<? endif; ?>
<form class="form-signin" action="" method="post">
        <h2 class="form-signin-heading">Please sign in</h2>
        <label for="user_login" class="sr-only">Email address</label>
        <input type="text" id="user_login" name="user_login" class="form-control" placeholder="User login" required autofocus>
        <label for="user_password" class="sr-only">Password</label>
        <input type="password" id="user_password" name="user_password" class="form-control" placeholder="Password" required> 
	<button class="btn btn-lg btn-primary btn-block" type="submit">Sign in</button>
	<? $this->display_template('hidden_fields.php', array('hidden_fields' => $data->get('hidden_fields'))); ?>
</form>
<? 
$links = array();
if($this->args()->get('register_url')) {
	$url = $this->args()->get('register_url', '/user/register');
	$links[] = "<a href=\"{$url}\" title=\"Register\">Register</a>";
}
if($this->args()->get('lost_password_url')) {
	$url = $this->args()->get('lost_password_url', '/user/lost-password');
	$links[] = "<a href=\"{$url}\" title=\"Lost password\">Forgot password?</a>";
}
if(!empty($links)): 
?>
<p>
<? echo implode(' | ', $links); ?>
</p>
<? endif; ?>
</div>
</div>
