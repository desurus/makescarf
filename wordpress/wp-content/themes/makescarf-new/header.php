<!DOCTYPE html>
<html lang="en">
    <head>
    <meta charset="<? bloginfo('charset'); ?>" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
		<title><?php
// Print the <title> tag based on what is being viewed.
global $page, $paged;

wp_title( '|', true, 'right' );

// Add the blog name.
bloginfo( 'name' );

// Add the blog description for the home/front page.
$site_description = get_bloginfo( 'description', 'display' );
if ( $site_description && ( is_home() || is_front_page() ) )
	echo " | $site_description";

// Add a page number if necessary:
if ( ( $paged >= 2 || $page >= 2 ) && ! is_404() )
	echo esc_html( ' | ' . sprintf( __( 'Page %s', 'twentyeleven' ), max( $paged, $page ) ) );

?></title>
<? wp_head(); ?>	
		<!-- Bootstrap -->	
		<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
		<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
		<!--[if lt IE 9]>
		  <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
		  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
		<![endif]-->
    </head>
    <body <? body_class(); ?>>
<? if(PM()->request()->get('iframe', 'false') != 'true'): ?>
	<header>
	    <div class="cart_account_bl" >
		    <div class="container" >
			    <div class="row" >
				    <div class="col-lg-5 col-md-6 col-sm-6 col-xs-1 resset_pad" >
					    <div class="mail_bl" >
						   <? PM()->display_snippet('header_mail.php'); ?> 
						</div>
					</div>
				    <div class="col-lg-7 col-md-6 col-sm-6 col-xs-11 top_bl_ac_who_cart resset_pad" >
					    <? PM()->display_snippet('top_cart_menu.php', array(), array( 'wrap_area' => false )); ?>
					   <? PM()->display_widget('\Pure\Widget\Woo\Cart\Widget', array("template" => "header_small")); ?> 
					</div>
				</div>
			</div>
		</div>

		<div class="container" >
			<div class="row padding_logo" >
				<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 resset_pad" >
				    <div class="bl_logo" >
					   <? PM()->display_snippet('header_logo.php'); ?> 
					</div>
				</div>
				<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 resset_pad" >
				    <div class="social_bl" >

					    <ul class="all_social_top" >
						    <li>
								<div class="join_us" >
									<span>join us here</span>
								</div>
						    </li>
							<li>
								<div class="group_soc" >
									<? PM()->display_snippet('social_links_block.php'); ?>	
								</div>
						    </li>
						</ul>
					</div>
				</div> 
			</div>
			<div class="row" >
				<div class="navbar navbar-inverse navbar-static-top" >
					<div class="navbar-header" >
						<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#responsive-menu" >
							<span class="sr-only" ></span>
							<span class="icon-bar" ></span>
							<span class="icon-bar" ></span>
							<span class="icon-bar" ></span>
						</button>
					</div>
					<div class="collapse navbar-collapse" id="responsive-menu" >
<? PM()->display_widget("\Pure\Widget\Menu\Items\Widget", array( 
"theme_location" => "main_menu",
"menu_class" => "nav navbar-nav top_menu",
"container" => "",
"template" => "default" 
)); ?>	
					</div>
				</div>
			</div>
		</div>	
	</header>
<? endif; ?>

	<div class="main_content" ><!-- main_content -->
