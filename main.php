<?php
/**
 * Plugin Name:       WPB Floating Menu or Categories
 * Plugin URI:        https://wpbean.com/product/wpb-floating-menu-or-categories/
 * Description:       Show floating menu or categories. 
 * Version:           1.0.8
 * Author:            wpbean
 * Author URI:        http://wpbean.com
 * Text Domain:       wpb-floating-menu-or-categories
 * Domain Path:       /languages
 */


// don't call the file directly
if ( !defined( 'ABSPATH' ) ) exit;



/**
 * Define constant
 */

define( 'WPB_FMC_PATH', trailingslashit( plugin_dir_path( __FILE__ ) ) );

define( 'WPB_FMC_Domain', 'wpb-floating-menu-or-categories' );

define( 'WPB_FMC_PLUGIN_DIR_FILE', __FILE__ );


/**
 * Plugin Class
 */

class WPB_FMC_Class {

    /**
     * The plugin path
     *
     * @var string
     */
    public $plugin_path;


    /**
     * The theme directory path
     *
     * @var string
     */
    public $theme_dir_path;


    /**
     * Initializes the WPB_FMC_Class() class
     *
     * Checks for an existing WPB_FMC_Class() instance
     * and if it doesn't find one, creates it.
     */
    public static function init() {
        static $instance = false;

        if ( ! $instance ) {
            $instance = new WPB_FMC_Class();

            $instance->plugin_init();
        }

        return $instance;
    }

    /**
     * Initialize the plugin
     *
     * @return void
     */
    function plugin_init() {
    	$this->theme_dir_path = apply_filters( 'wpb_fmc_dir_path', 'wpb-floating-menu-or-categories/' );

    	$this->file_includes();

        add_action( 'init', array( $this, 'localization_setup' ) );

        add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), array( $this, 'plugin_actions_links' ));
    }


    /**
     * Load the required files
     *
     * @return void
     */

    function file_includes() {
        require_once dirname( __FILE__ ) . '/inc/wpb_functions.php';
        require_once dirname( __FILE__ ) . '/inc/wpb_shortcode.php';
        require_once dirname( __FILE__ ) . '/inc/wpb_scripts.php';
        require_once dirname( __FILE__ ) . '/admin/class.settings-api.php';
        require_once dirname( __FILE__ ) . '/admin/plugin-settings.php';
        require_once dirname( __FILE__ ) . '/admin/category-icon.php';
        if ( ! class_exists( 'TGM_Plugin_Activation' ) ) {
            require_once dirname( __FILE__ ) . '/inc/class-tgm-plugin-activation.php';
        }
    }

    /**
     * Plugin action links
     */
    
    function plugin_actions_links( $links ) {
        if( is_admin() ){
            $links[] = '<a href="http://wpbean.com/support/" target="_blank">'. esc_html__( 'Support', WPB_FMC_Domain ) .'</a>';
            $links[] = '<a href="http://docs.wpbean.com/docs/wpb-floating-menu-or-categories/installing/" target="_blank">'. esc_html__( 'Documentation', WPB_FMC_Domain ) .'</a>';
        }
        return $links;
    }


    /**
     * Initialize plugin for localization
     *
     * @uses load_plugin_textdomain()
     */
    public function localization_setup() {
        load_plugin_textdomain( WPB_FMC_Domain, false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
    }


    /**
     * Get the plugin path.
     *
     * @return string
     */
    public function plugin_path() {
        if ( $this->plugin_path ) return $this->plugin_path;

        return $this->plugin_path = untrailingslashit( plugin_dir_path( __FILE__ ) );
    }

    /**
     * Get the template path.
     *
     * @return string
     */
    public function template_path() {
        return $this->plugin_path() . '/templates/';
    }

}

/**
 * Initialize the plugin
 */

function wpb_fmc_plugin_init() {
    return WPB_FMC_Class::init();
}

// kick it off
wpb_fmc_plugin_init();