<?
$this->include_js('jquery');
?>
<script type="text/javascript">
jQuery(function($){
	$('.re-btn').click(function(){
		$('#comment_form').trigger('submit');
		return false;
	})
})
</script>
<div class="re-form">
<? if($data->get('errors')): ?>
<? foreach($data->get('errors') as $error): ?>
<div class="alert warning"><? echo $error; ?></div>
<? endforeach; ?>
<? endif; ?>
<? if($data->get('messages')): ?>
<? foreach($data->get('messages') as $message): ?>
<div class="alert notice"><? echo $message; ?></div>
<? endforeach; ?>
<? endif; ?>
						<form action="" method="post" id="comment_form">
							<label for="comment_author">Представтесь</label>
							<input id="comment_author" required="required" type="text" name="comment_author">
							<textarea name="comment_content" required="required" id="comment_content"></textarea>
							<input type="hidden" name="comment_post_id" value="<? echo $data->get('comment_post_id'); ?>" />
							<p>
								<input class="btn re-btn" type="button" name="button" value="Отправить отзыв">
							</p>
						</form>
</div>
