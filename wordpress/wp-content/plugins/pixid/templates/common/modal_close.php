<script type="text/javascript">
jQuery(function($){
<? if(@$refresh_parent): ?>
	window.parent.PM.closeModal(true);
<? else: ?>
	window.parent.PM.closeModal(false);
<? endif; ?>
});
</script>
