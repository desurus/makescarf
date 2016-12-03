<?php
/**
 * The Template for displaying all single products
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you (the theme developer).
 * will need to copy the new files to your theme to maintain compatibility. We try to do this.
 * as little as possible, but it does happen. When this occurs the version of the template file will.
 * be bumped and the readme will list any important changes.
 *
 * @see 	    http://docs.woothemes.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
global $product;
?>
<? get_header(); ?>

<div class="container default" >
<? while(have_posts()): the_post(); ?>
		    <div class="row" >
				
				<h2 class="title_ideas" ><? echo "Edit saved scarf!"; ?></h2>
				
			</div>	
		    <div class="row" >
				<div class="co">
SUBMIT TEXT, PLACE AN ORDER . OUR DESIGNER WILL SEND THE ARTWORK FOR YOUR APPROVAL SHORTLY
<? PM()->display_widget("\MakeScarf\Widget\Constructor\Widget", array(
	"template" => "default",
	"scarf_id" => $product->post->ID
)); ?>
</div>		
			</div>	
<? endwhile; ?>
		</div>
<? get_footer(  ); ?>
