<? 
/**
This widget template uses a basic Bootstrap markup for login form, without any other blocks markup, only fields...
*/
?>

<? if($data->get('errors')): ?>
<? foreach($data->get('errors') as $error): ?>
<div class="alert danger"><? echo $error; ?></div>
<? endforeach; ?>
<? endif; ?>
<? echo $form->render_header(); ?>
<? foreach($form->get_elements() as $element):  ?>
<? if($element->get_option('type') == 'hidden') { echo $element->render_input(); continue; }?>	
<label for="<? echo $element->get_id(); ?>"><? echo $element->get_option('label'); ?></label>
<? echo $element->render_input(); ?>
<? endforeach; ?> 
	<button class="btn btn-lg btn-primary btn-block" type="submit">Sign in</button>
	<? $this->display_template('hidden_fields.php', array('hidden_fields' => $data->get('hidden_fields'))); ?>
<? echo $form->render_footer(); ?>
