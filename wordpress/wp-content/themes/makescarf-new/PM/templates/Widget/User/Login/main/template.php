<? 
/**
This widget template uses a basic Bootstrap markup for login form, without any other blocks markup, only fields...
*/
?>
<div class="l-wrap">

                                                                <span class="l-caption">Already registered?</span>
                                                                <p class="l-sub-caption">If you have an account with us, please log in.</p>



                                                                <div class="l-form">
                                                                        <span class="required">* Required Fields</span>
<? if($data->get('errors')): ?>
<? foreach($data->get('errors') as $error): ?>
<div class="alert danger"><? echo $error; ?></div>
<? endforeach; ?>
<? endif; ?>
<? echo $form->render_header(); ?>
<? foreach($form->get_elements() as $element):  ?>
<? if($element->get_option('type') == 'hidden') { echo $element->render_input(); continue; }?>	
<label for="<? echo $element->get_id(); ?>"><? echo $element->get_option('label'); ?>
<? echo $element->render_input(); ?>

</label>
<? endforeach; ?>
	<button class="btn btn-style" type="submit">Login</button>
		<a href="<? echo wp_lostpassword_url( '/account' ); ?>" class="l-link">Forgot Your Password?</a>
<? echo $form->render_footer(); ?>
</div>
</div>
