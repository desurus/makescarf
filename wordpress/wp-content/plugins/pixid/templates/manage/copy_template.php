<div class="">
<form action="" method="post">
<p><? _e('Please, specify the destination template name:', WAMT_DOMAIN); ?></p>
<p><input type="text" name="pm_new_template_name" value="<? echo @$new_template_name; ?>" placeholder="<? __('New template name', WAMT_DOMAIN); ?>"/></p>
<p><? echo sprintf(
	__("Template %s from %s will be copied to %s."), 
	"<span class=\"template_name\">$template_name</span>", 
	$this->Path()->get_code($template->get_template_directory()), 
	$this->Path()->get_code($new_template->get_template_directory())
); 
?></p>
<p></p>
<p><? _e("Please, do not forget to manually set a new template name in call template code. With template => \"\" argument."); ?></p>
<p>
<? $this->render("common/form/submit_button.php", array("title" => __("Copy", WAMT_DOMAIN))); ?>
<? $this->render("common/form/close_button.php", array()); ?>
</p>
</form>
</div>
