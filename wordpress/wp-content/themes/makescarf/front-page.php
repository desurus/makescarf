<? get_header(); ?>
<? MakeScarf_Theme::Me()->DisplayTemplatePart('slider.php'); ?>	
				<? MakeScarf_Theme::Me()->DisplayTemplatePart('how-it-works.php');?>	
				<div class="wrapColumnBox">
					<? MakeScarf_Theme::Me()->DisplayTemplatePart('video-block-on-main.php'); ?>	
					<? MakeScarf_Theme::Me()->DisplayTemplatePart('library-go-block.php'); ?>	
				</div>
				<? MakeScarf_Theme::Me()->DisplayTemplatePart('text-ideas-block.php'); ?>	
				<div class="botAboutBox">
<? the_post(); the_content(); ?>	
				</div>
<? get_footer(); ?>
