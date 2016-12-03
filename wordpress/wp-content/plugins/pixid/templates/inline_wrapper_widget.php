<div class="pm-wrapper pm-snippet pm-widget" data-wrapper-uid="<? echo $uid; ?>" id="pm-wrapper-<? echo $uid; ?>" title="Pure Manager - Edit Widget">
<? echo $code; ?>
<div class="pm-highlight" data-wrapper-uid="<? echo $uid; ?>" id="pm-highlight-<? echo $uid; ?>">
	<div class="pm-buttons">
		<ul>
			<? if(!empty($edit_buttons)): ?>
<? foreach($edit_buttons as $edit_button): ?>
<li>
<? if($edit_button instanceof \Pure\Editor\Button): ?>
<? $edit_button->render(); continue; ?>
<? endif; ?>
<a 
class="pm-icon pm-button  
pm-icon-<? echo $edit_button['icon']; ?> 
dashicons-before 
dashicons-<? echo $edit_button['icon']; ?>" 
title="<? echo $edit_button['title']?>" 
href="<? echo $edit_button['link']; ?>" 
target="_blank"></a>
</li>
<? endforeach; ?>
			<? endif; ?>			
		</ul>
	</div>
	
</div>
</div>
