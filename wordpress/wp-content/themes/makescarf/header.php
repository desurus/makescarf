<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta charset="<? bloginfo('charset'); ?>" />
    <!--[if IE]><script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
     <title><?php
	/*
	 * Print the <title> tag based on what is being viewed.
	 */
	global $page, $paged;

	wp_title( '|', true, 'right' );

	// Add the blog name.
	bloginfo( 'name' );

	// Add the blog description for the home/front page.
	$site_description = get_bloginfo( 'description', 'display' );
	if ( $site_description && ( is_home() || is_front_page() ) )
		echo " | $site_description";

	// Add a page number if necessary:
	if ( $paged >= 2 || $page >= 2 )
		echo ' | ' . sprintf( __( 'Page %s', 'twentyten' ), max( $paged, $page ) );

	?></title>
<link rel="profile" href="http://gmpg.org/xfn/11" />
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
<link rel="stylesheet" href="<? echo get_template_directory_uri(); ?>/css/reset.css" type="text/css" />
<link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo( 'stylesheet_url' ); ?>" />
<? wp_head(); ?>
	<!--[if lte IE 6 ]><script type="text/javascript">window.location.href="ie6_close/index_ru.html";</script><![endif]-->
		<!--[if lt IE 9]><link rel="stylesheet" type="text/css" href="<? echo get_template_directory_uri(); ?>/<? echo get_template_directory_uri(); ?>/css/all_ie.css"><![endif]-->
    <!--[if lt IE 10]><script type="text/javascript" src="<? echo get_template_directory_uri(); ?>/js/pie.js"></script><![endif]-->
</head>

<body>

<div id="wrapper">
    <header id="header" class="header clearfix">
	<div class="topTabs">

<span class="twoTabs"><a href="/cart"><img src="<?=get_template_directory_uri(); ?>/img/ShoppingCart.png" />Cart<? $count = MakeScarf_Theme::Me()->CountCartItems(); if($count>0) echo " ({$count}) ";?></a></span>		
<span class="oneTabs"><a href="/account">My account</a></span>
    		<span class="twoTabs"><a href="/wholesalers">WHOLESALERS</a></span>  		
    	</div>
<? echo MakeScarf_Theme::Me()->options->GetOption('social_html'); ?>		
<div class="logo"><a href="<? bloginfo('siteurl'); ?>"><img src="<? echo get_template_directory_uri(); ?>/img/logo.png" alt="" /></a></div>
		<nav>
		<a target="_blank" href="<? echo MakeScarf_Theme::Me()->options->GetOption('boutique_url'); ?>" class="asideBtn"></a>
<?
$args = array(
			//'name' => 'Top menu',
			'location' => 'top-menu',
			'container' => '',
			'menu_class' => 'menu',
			'link_after' => '<span></span>'
		);
	wp_nav_menu($args);
?>
			
		</nav>
    </header><!-- /header-->
    <section id="middle">
        <div id="container" class="clearfix">
			<div class="content">
						
