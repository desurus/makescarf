<?
$form = $data->get('form');
?>
<? echo $form->render_header(); ?>
<? foreach($form->get_elements() as $element): ?>	
				<p>
<? echo $element->render_input(); ?>
<label for="<? echo $element->get_id(); ?>"><? echo $element->get_option('label', $element->get_option('title')); ?></label>
					</p>
<? endforeach; ?>
<input class="btn btn-size-1" type="submit" name="button" value="<? echo $this->args()->get('submit_title', __('Send', 'wamt')); ?>">
<? echo $form->render_footer(); ?>
