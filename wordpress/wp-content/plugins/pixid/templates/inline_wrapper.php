<div class="pm-wrapper pm-snippet" <? if(!empty($args['wrapper_style'])): ?>style="<? echo $args['wrapper_style']; ?>"<? endif;?> data-wrapper-uid="<? echo $uid; ?>" id="pm-wrapper-<? echo $uid; ?>" title="Pure Manager - Edit element">
<? echo $code; ?>
<div class="pm-highlight" data-wrapper-uid="<? echo $uid; ?>" id="pm-highlight-<? echo $uid; ?>">
	<div class="pm-buttons">
		<ul>
	<? foreach($edit_buttons as $edit_button): ?>
<li>
<? $edit_button->render(); ?>
</li>
<? endforeach; ?>	
		</ul>
	</div>
	
	<div class="pm-border pm-border-top"></div>
	<div class="pm-border pm-border-right"></div>
	<div class="pm-border pm-border-bottom"></div>
	<div class="pm-border pm-border-left"></div>
</div>
</div>
