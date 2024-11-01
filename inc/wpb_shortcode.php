<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly 


/**
 * Floating Menu ShortCode
 */

add_shortcode( 'wpb-fmc-floating-menu', 'wpb_fmc_floating_menu' );

function wpb_fmc_floating_menu( $atts = array() ){

	$shortcode_atts = array(
		'skin'			=> wpb_fmc_get_option( 'wpb_fmc_menu_skin', 'wpb_fmc_memu_settings', 'default' ),
		'menu_id'		=> wpb_fmc_get_option( 'wpb_fmc_menu_id', 'wpb_fmc_memu_settings', '' ),  // Accepts a menu ID, slug, name, or object.
		'depth'			=> wpb_fmc_get_option( 'wpb_fmc_menu_depth', 'wpb_fmc_memu_settings', 0 ),  // Accepts a menu ID, slug, name, or object.
		'position'		=> wpb_fmc_get_option( 'wpb_fmc_menu_position', 'wpb_fmc_memu_settings', 'right' ),
		'disable_phone'	=> wpb_fmc_get_option( 'wpb_fmc_disable_phone', 'wpb_fmc_style_settings', 'on' ),
	);

	extract( shortcode_atts( $shortcode_atts, $atts ) );

	$css_classes = array(
		'wpb-fmc-floating-menu',
		'wpb-fmc-floating-menu-' . $position,
		'wpb-fmc-floating-menu-or-category-' . $skin,
		'wpb-fmc-floating-menu-or-category-disable-phone-' . $disable_phone,
	);

	ob_start();
	?>
		<div class="<?php echo esc_attr( implode( ' ', $css_classes ) ); ?>">
			<?php
				wp_nav_menu( array(
					'menu'   	=> $menu_id,
					'depth'		=> $depth,
				));
			?>
		</div>
	<?php
	return ob_get_clean();
}



/**
 * Floating Category ShortCode
 */


add_shortcode( 'wpb-fmc-floating-category', 'wpb_fmc_floating_category' );

function wpb_fmc_floating_category( $atts = array() ){

	$shortcode_atts = array(
		'skin'			=> wpb_fmc_get_option( 'wpb_fmc_category_skin', 'wpb_fmc_category_settings', 'default' ),
		'position'		=> wpb_fmc_get_option( 'wpb_fmc_cat_position', 'wpb_fmc_category_settings', 'left' ),
		'taxonomy'		=> wpb_fmc_get_option( 'wpb_fmc_taxonomy', 'wpb_fmc_category_settings', 'category' ),
		'depth'			=> wpb_fmc_get_option( 'wpb_fmc_cat_depth', 'wpb_fmc_category_settings', '0' ),
		'number'		=> wpb_fmc_get_option( 'wpb_fmc_cat_number', 'wpb_fmc_category_settings', '0' ),
		'hierarchical'	=> wpb_fmc_get_option( 'wpb_fmc_cat_hierarchical', 'wpb_fmc_category_settings', 'off' ),
		'include'		=> wpb_fmc_get_option( 'wpb_fmc_cat_include', 'wpb_fmc_category_settings', '' ),
		'exclude'		=> wpb_fmc_get_option( 'wpb_fmc_cat_exclude', 'wpb_fmc_category_settings', '' ),
		'hide_empty'	=> wpb_fmc_get_option( 'wpb_fmc_cat_hide_empty', 'wpb_fmc_category_settings', 'on' ),
		'show_count'	=> wpb_fmc_get_option( 'wpb_fmc_cat_show_count', 'wpb_fmc_category_settings', 'on' ),
		'disable_phone'	=> wpb_fmc_get_option( 'wpb_fmc_disable_phone', 'wpb_fmc_style_settings', 'on' ),
		'orderby' 		=> 'name',
		'order' 		=> 'ASC',
	);

	extract( shortcode_atts( $shortcode_atts, $atts ) );

	$css_classes = array(
		'wpb-fmc-floating-category',
		'wpb-fmc-floating-category-' . $position,
		'wpb-fmc-floating-menu-or-category-' . $skin,
		'wpb-fmc-floating-menu-or-category-disable-phone-' . $disable_phone,
	);

	wp_enqueue_style('wpb_fmc_font_awesome');
	do_action( 'wpb_fmc_after_frontend_font_icons' );

	$include = explode(',', $include);

	$exclude = explode(',', $exclude);

	ob_start();
	?>
		<div class="<?php echo esc_attr( implode( ' ', $css_classes ) ); ?>">
			<ul>
				<?php 
				    $args = array(
						'show_option_all'    => '',
						'orderby'            => $orderby,
						'order'              => $order,
						'style'              => 'list',
						'show_count'         => ( $show_count == 'on' ? 1 : 0 ),
						'hide_empty'         => ( $hide_empty == 'on' ? 1 : 0 ),
						'exclude'            => $exclude,
						'exclude_tree'       => '',
						'include'            => $include,
						'hierarchical'       => ( $hierarchical == 'off' ? 1 : 0 ),
						'title_li'           => '',
						'number'             => ( $number == 0 ? null : $number ),
						'echo'               => 1,
						'depth'              => $depth,
						'taxonomy'           => $taxonomy,
						'walker'             => new WPB_FMC_Category_Walker,
				    );

				    $args = apply_filters( 'wpb_fmc_wp_list_categories_args', $args );

				    wp_list_categories( $args );
				?>
			</ul>
		</div>
	<?php
	return ob_get_clean();
}
