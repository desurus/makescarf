<?
$form = $data->get('form');
?>
<? if($data->get('errors')): ?>
<? $this->display_template('errors.php', $data); ?>
<? endif; ?>
<form action="<? echo $form->get_option('action'); ?>" method="<? echo $form->get_option('method'); ?>">
<? 
foreach($form->get_elements() as $element): 
	$element->set_option('class', ''); 
	$element->set_option('placeholder', mb_strtoupper($element->get_option('placeholder')));
?>
									<label>
									<? echo $element->get_option('label'); ?> <? if($element->get_option('required', false)): ?>&nbsp;*<? endif; ?>
									<? echo $element->render_input(); ?>	
									</label>
<? endforeach; ?>							
									<button type="submit" class="btn btn-style btn-submit">Create an account</button>
								</form>
