<script type="text/javascript">
jQuery(function($){
	$('#login_button').click(function(){
		$(this).parent().trigger('submit');
		return false;
	})
})
</script>
<div class="reg-main">
            <div class="center">
	    <form method="post" action="<? echo wp_login_url(); ?>" id="wp_login_form" name="wp_login_form" >		
<input type="text" value="" name="log" placeholder="Логин:" />
		<input type="password" name="pwd" value="" placeholder="Пароль:" />
		<input type="hidden" name="redirect_url" value="<? echo $_SERVER['REQUEST_URI']; ?>" />
		<a href="#" id="login_button" class="button">войти</a>
</form>
            </div>
        </div>
