</div>
        </div><!-- #container-->
    </section><!-- #middle-->
</div><!-- #wrapper -->

<footer id="footer" class="footer">
	<div class="footerLogo"><a href="<? bloginfo('siteurl'); ?>"><img src="<? echo get_template_directory_uri(); ?>/img/footer-logo.png" alt="" /></a></div>
<? wp_nav_menu(array('theme_location' => 'bottom', 'menu_container' => '', 'menu_class' => '')); ?>	
<p class="copy">© 2012 All rights reserved.</p>
</footer><!-- #footer -->
<? wp_footer(); ?>
</body>
</html>
