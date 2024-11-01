<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly 

/**
 * Get template part implementation for WPB Post Slider
 *
 * Looks at the theme directory first
 */
function wpb_fmc_get_template_part( $slug, $name = '' ) {
    $wpb_fmc_plugin_init = wpb_fmc_plugin_init::init();

    $templates = array();
    $name = (string) $name;

    // lookup at theme/slug-name.php or wpb-floating-menu-or-categories/slug-name.php
    if ( '' !== $name ) {
        $templates[] = "{$slug}-{$name}.php";
        $templates[] = $wpb_fmc_plugin_init->theme_dir_path . "{$slug}-{$name}.php";
    }

    $template = locate_template( $templates );

    // fallback to plugin default template
    if ( !$template && $name && file_exists( $wpb_fmc_plugin_init->template_path() . "{$slug}-{$name}.php" ) ) {
        $template = $wpb_fmc_plugin_init->template_path() . "{$slug}-{$name}.php";
    }

    // if not yet found, lookup in slug.php only
    if ( !$template ) {
        $templates = array(
            "{$slug}.php",
            $wpb_fmc_plugin_init->theme_dir_path . "{$slug}.php"
        );

        $template = locate_template( $templates );
    }

    if ( $template ) {
        load_template( $template, false );
    }
}

/**
 * Include a template by precedance
 *
 * Looks at the theme directory first
 *
 * @param  string  $template_name
 * @param  array   $args
 *
 * @return void
 */
function wpb_fmc_get_template( $template_name, $args = array() ) {
    $wpb_fmc_plugin_init = wpb_fmc_plugin_init::init();

    if ( $args && is_array($args) ) {
        extract( $args );
    }

    $template = locate_template( array(
        $wpb_fmc_plugin_init->theme_dir_path . $template_name,
        $template_name
    ) );

    if ( ! $template ) {
        $template = $wpb_fmc_plugin_init->template_path() . $template_name;
    }

    if ( file_exists( $template ) ) {
        include $template;
    }
}


/**
 * Adding the menu page
 */

if( !function_exists('wpb_fmc_register_menu_page') ){
    function wpb_fmc_register_menu_page() {
        add_menu_page(
            esc_html__( 'WPB Floating Menu or Categories', WPB_FMC_Domain ),
            esc_html__( 'Floating Menu', WPB_FMC_Domain ),
            apply_filters( 'wpb_fmc_settings_user_capability', 'manage_options' ),
            WPB_FMC_Domain.'-about',
            'wpb_fmc_get_menu_page',
            'dashicons-paperclip'
        );
    }
}
add_action( 'admin_menu', 'wpb_fmc_register_menu_page' );


/**
 * Getting the menu page
 */

if( !function_exists('wpb_fmc_get_menu_page') ){
    function wpb_fmc_get_menu_page(){
        require (   WPB_FMC_PATH . 'admin/admin-page.php' );
    }
}


/**
 * Get Menus for plugins settings
 */

function wpb_fmc_get_menus(){
    $wpb_fmc_menus  = get_terms('nav_menu');
    $output         = array();

    foreach ( $wpb_fmc_menus as $wpb_fmc_menu ) {
        $output[$wpb_fmc_menu->term_id] = $wpb_fmc_menu->name;
    }

    return $output;
}

/**
 * Get taxonomy for plugins settings
 */

function wpb_fmc_get_taxonomy(){
    $args       = array(
        'public'   => true,
    ); 
    $tax_output = 'objects';
    $taxonomies = get_taxonomies( $args, $tax_output );
    $output     = array();

    foreach ( $taxonomies as $taxonomy ) {
        $output[$taxonomy->name] = $taxonomy->labels->name;
    }

    return $output;
}


/**
 * Get the setting values 
 */

if( !function_exists('wpb_fmc_get_option') ){
    function wpb_fmc_get_option( $option, $section, $default = '' ) {
     
        $options = get_option( $section );
     
        if ( isset( $options[$option] ) ) {
            return $options[$option];
        }
     
        return $default;
    }
}


/**
 * TGM Config
 */


add_action( 'tgmpa_register', 'wpb_fmc_register_required_plugins' );

if(!function_exists('wpb_fmc_register_required_plugins')){
    function wpb_fmc_register_required_plugins() {

        $plugins = array(
            array(
                'name'      => esc_html__( 'Menu Icons by ThemeIsle', 'opticals' ),
                'slug'      => 'menu-icons',
                'required'  => false,
            ),
        );

        $config = array(
            'id'           => 'wpb_fmc',
            'default_path' => '',
            'menu'         => 'tgmpa-install-plugins',
            'parent_slug'  => 'themes.php',
            'capability'   => 'edit_theme_options',
            'has_notices'  => true,
            'dismissable'  => true,
            'dismiss_msg'  => '',
            'is_automatic' => false,
            'message'      => '',
        );

        tgmpa( $plugins, $config );
    }
}



/**
 * Enable Floating Menu & categoey Globally
 */

add_action( 'wp_footer', 'wpb_fmc_enable_menu_and_category_globally' );

if(!function_exists('wpb_fmc_enable_menu_and_category_globally')){
    function wpb_fmc_enable_menu_and_category_globally() {
        $enable_menu_globally       = wpb_fmc_get_option( 'wpb_fmc_enable_menu_globally', 'wpb_fmc_memu_settings', 'on' );
        $enable_category_globally   = wpb_fmc_get_option( 'wpb_fmc_enable_category_globally', 'wpb_fmc_category_settings', 'on' );

        if( $enable_menu_globally == 'on' ){
            echo wpb_fmc_floating_menu();
        }

        if( $enable_category_globally == 'on' ){
            echo wpb_fmc_floating_category();
        }
    }
}




/**
 * List Category Walker
 */

class WPB_FMC_Category_Walker extends Walker_Category {

    public function start_el( &$output, $category, $depth = 0, $args = array(), $id = 0 ) {
        /** This filter is documented in wp-includes/category-template.php */
        $cat_name = apply_filters(
            'list_cats',
            esc_attr( $category->name ),
            $category
        );

        // Don't generate an element if the category name is empty.
        if ( ! $cat_name ) {
            return;
        }

        $wpb_fmc_cat_count  = '';
        $wpb_fmc_cat_icon   = '';


        // Getting the category icon [ texonomoy meta ]
        $wpb_fmc_term_meta = get_option( "taxonomy_$category->term_id" );

        if ( ! empty( $wpb_fmc_term_meta['wpb_fmc_cat_icons'] ) ) {
            $wpb_fmc_cat_icon = '<i class="'. esc_attr( $wpb_fmc_term_meta['wpb_fmc_cat_icons'] ) .'"></i>';
        }

        // Adding Post count 
        if ( ! empty( $args['show_count'] ) ) {
            $cat_name = '<span class="wpb-fmc-cat-name">'. esc_html( $cat_name ) .' ('. number_format_i18n( esc_html( $category->count ) ) .')</span>';
        }else{
            $cat_name = '<span class="wpb-fmc-cat-name">'. esc_html( $cat_name ) .'</span>';
        }

        $link = '<a href="' . esc_url( get_term_link( $category ) ) . '" ';

        if ( $args['use_desc_for_title'] && ! empty( $category->description ) ) {
            $link .= 'title="' . esc_attr( strip_tags( apply_filters( 'category_description', $category->description, $category ) ) ) . '"';
        }

        $link .= '>' . $wpb_fmc_cat_icon;

        $link .= $cat_name;

        $link .= '</a>';



        if ( 'list' == $args['style'] ) {
            $output .= "\t<li";
            $css_classes = array(
                'cat-item',
                'cat-item-' . $category->term_id,
            );

            $termchildren = get_term_children( $category->term_id, $category->taxonomy );

            if( count($termchildren)>0 ){
                $css_classes[] =  'cat-item-have-child';
            }

            if ( ! empty( $args['current_category'] ) ) {
                $_current_category = get_term( $args['current_category'], $category->taxonomy );
                if ( $category->term_id == $args['current_category'] ) {
                    $css_classes[] = 'current-cat';
                } elseif ( $category->term_id == $_current_category->parent ) {
                    $css_classes[] = 'wpb-fmc-current-cat-parent';
                }
            }

            $css_classes = implode( ' ', apply_filters( 'category_css_class', $css_classes, $category, $depth, $args ) );

            $output .=  ' class="' . $css_classes . '"';
            $output .= ">$link\n";
        } else {
            $output .= "\t$link<br />\n";
        }
    }
}