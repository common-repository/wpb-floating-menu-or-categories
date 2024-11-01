<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly 


/**
 * Add Plugin Scripts
 */

function wpb_fmc_register_scripts(){

	wp_register_style('wpb_fmc_font_awesome', plugins_url('../assets/css/font-awesome.min.css', __FILE__ ), '', '4.7.0', false);
	wp_enqueue_style('wpb_fmc_main_css', plugins_url('../assets/css/main.css', __FILE__ ), '', '1.0', false);
	wp_enqueue_script('wpb_fmc_main_js', plugins_url('../assets/js/main.js', __FILE__), array('jquery'), '1.0', false);	

}
add_action( 'wp_enqueue_scripts', 'wpb_fmc_register_scripts', 20 ); 



/**
 * Admin Scripts
 */

add_action( 'admin_enqueue_scripts', 'wpb_fmc_admin_font_icons' );

function wpb_fmc_admin_font_icons(){

	$tax = sanitize_key(@$_REQUEST['taxonomy']);

	if( $tax ){
		wp_enqueue_style('wpb_fmc_admin_font_awesome', plugins_url('../assets/css/font-awesome.min.css', __FILE__ ), '', '4.7.0', false);
		do_action( 'wpb_fmc_after_admin_font_icons' );
	}

	wp_register_style('wpb_fmc_admin_css', plugins_url('../assets/css/admin.css', __FILE__ ), '', '1.0', false);
}


/**
 * Custom CSS
 */

add_action( 'wp_enqueue_scripts', 'wpb_fmc_custom_css' );

function wpb_fmc_custom_css(){

	wp_enqueue_style('wpb_fmc_main_css', plugins_url('../assets/css/main.css', __FILE__ ), '', '1.0', false);

    $primary_color 		= wpb_fmc_get_option( 'primary_color', 'wpb_fmc_style_settings', '#ff1053' );
    $secondary_color 	= wpb_fmc_get_option( 'secondary_color', 'wpb_fmc_style_settings', '#db0843' );

    $custom_css = "
    .wpb-fmc-floating-menu-or-category-default ul li a,
    .wpb-fmc-floating-menu-or-category-floating_icons ul li > a {
        background: {$primary_color};
    }";

    $custom_css .= "
    .wpb-fmc-floating-menu-or-category-floating_icons ul li > a:hover,
    .wpb-fmc-floating-menu-or-category-default ul li > a:focus,
	.wpb-fmc-floating-menu-or-category-default ul li > a:hover,
	.wpb-fmc-floating-menu-or-category-default ul li > a:active,
    .wpb-fmc-floating-menu-or-category-floating_icons ul li.current-cat > a,
    .wpb-fmc-floating-menu-or-category-floating_icons ul li.current-menu-item > a {
        background: {$secondary_color};
    }";

    wp_add_inline_style( 'wpb_fmc_main_css', $custom_css );

}