<?php

/**
 * Plugin settings
 */

if ( !class_exists('WPB_FMC_WeDevs_Settings_API_Init' ) ):
class WPB_FMC_WeDevs_Settings_API_Init {

    private $settings_api;

    function __construct() {
        $this->settings_api = new WPB_FMC_WeDevs_Settings_API;

        add_action( 'admin_init', array($this, 'admin_init') );
        add_action( 'admin_menu', array($this, 'admin_menu') );
    }

    function admin_init() {

        //set the settings
        $this->settings_api->set_sections( $this->get_settings_sections() );
        $this->settings_api->set_fields( $this->get_settings_fields() );

        //initialize settings
        $this->settings_api->admin_init();
    }

    function admin_menu() {
        add_submenu_page( 
            WPB_FMC_Domain . '-about',
            esc_html__( 'WPB Floating Menu or Categories Settings', WPB_FMC_Domain ),
            esc_html__( 'Settings', WPB_FMC_Domain ),
            apply_filters( 'wpb_fmc_settings_user_capability', 'delete_posts' ),
            WPB_FMC_Domain . '-settings',
            array( $this, 'plugin_page' )
        );
    }

    function get_settings_sections() {
        $sections = array(
            array(
                'id'    => 'wpb_fmc_memu_settings',
                'title' => esc_html__( 'Menu Settings', WPB_FMC_Domain )
            ),
            array(
                'id'    => 'wpb_fmc_category_settings',
                'title' => esc_html__( 'Category Settings', WPB_FMC_Domain )
            ),
            array(
                'id'    => 'wpb_fmc_style_settings',
                'title' => esc_html__( 'Style Settings', WPB_FMC_Domain )
            ),
        );
        return $sections;
    }

    /**
     * Returns all the settings fields
     *
     * @return array settings fields
     */
    function get_settings_fields() {
        $settings_fields = array(
            'wpb_fmc_memu_settings' => array(
                array(
                    'name'      => 'wpb_fmc_enable_menu_globally',
                    'label'     => esc_html__( 'Enable Floating Menu Globally', WPB_FMC_Domain ),
                    'desc'      => esc_html__( 'Enable floating menu globally for entire site.', WPB_FMC_Domain ),
                    'type'      => 'checkbox',
                    'default'   => 'on',
                ),
                array(
                    'name'    => 'wpb_fmc_menu_id',
                    'label'   => esc_html__( 'Select a menu', WPB_FMC_Domain ),
                    'desc'    => esc_html__( 'Select a menu for floating menu.', WPB_FMC_Domain ),
                    'type'    => 'select',
                    'options' => wpb_fmc_get_menus(),
                ),
                array(
                    'name'              => 'wpb_fmc_menu_depth',
                    'label'             => esc_html__( 'Menu Depth', WPB_FMC_Domain ),
                    'desc'              => esc_html__( 'How many levels of the hierarchy are to be included. 0 means all. Default 0.', WPB_FMC_Domain ),
                    'min'               => 0,
                    'max'               => 5,
                    'step'              => '1',
                    'type'              => 'number',
                    'default'           => 0,
                ),
                array(
                    'name'    => 'wpb_fmc_menu_position',
                    'label'   => esc_html__( 'Select Menu Position', WPB_FMC_Domain ),
                    'desc'    => esc_html__( 'Select floating menu position. Default right.', WPB_FMC_Domain ),
                    'type'    => 'select',
                    'default' => 'right',
                    'options' => array(
                        'left'      => esc_html__( 'Left', WPB_FMC_Domain ),
                        'right'     => esc_html__( 'Right', WPB_FMC_Domain ),
                    )
                ),
                array(
                    'name'    => 'wpb_fmc_menu_skin',
                    'label'   => esc_html__( 'Select Menu Skin', WPB_FMC_Domain ),
                    'desc'    => esc_html__( 'Select a skin for floating menu. ( Free version has only two skins. )', WPB_FMC_Domain ),
                    'type'    => 'select',
                    'default' => 'default',
                    'options' => apply_filters( 'wpb_fmc_skins', array(
                        'default'           => esc_html__( 'Default', WPB_FMC_Domain ),
                        'floating_icons'    => esc_html__( 'Floating Icons', WPB_FMC_Domain ),
                    )),
                ),
            ),
            'wpb_fmc_category_settings' => array(
                array(
                    'name'      => 'wpb_fmc_enable_category_globally',
                    'label'     => esc_html__( 'Enable Floating Category Globally', WPB_FMC_Domain ),
                    'desc'      => esc_html__( 'Enable floating category globally for entire site.', WPB_FMC_Domain ),
                    'type'      => 'checkbox',
                    'default'   => 'on',
                ),
                array(
                    'name'    => 'wpb_fmc_taxonomy',
                    'label'   => esc_html__( 'Select a Taxonomy', WPB_FMC_Domain ),
                    'desc'    => esc_html__( 'Select a taxonomy for floating category. Default: Post Category', WPB_FMC_Domain ),
                    'type'    => 'select',
                    'options' => wpb_fmc_get_taxonomy(),
                    'default' => 'category',
                ),
                array(
                    'name'              => 'wpb_fmc_cat_depth',
                    'label'             => esc_html__( 'Category Depth', WPB_FMC_Domain ),
                    'desc'              => esc_html__( 'Category depth. Used for tab indentation. Default 0', WPB_FMC_Domain ),
                    'min'               => 0,
                    'max'               => 5,
                    'step'              => '1',
                    'type'              => 'number',
                    'default'           => 0,
                ),
                array(
                    'name'              => 'wpb_fmc_cat_number',
                    'label'             => esc_html__( 'Number of Categories', WPB_FMC_Domain ),
                    'desc'              => esc_html__( 'Default 0, it will show alls.', WPB_FMC_Domain ),
                    'min'               => 0,
                    'max'               => 100,
                    'step'              => '1',
                    'type'              => 'number',
                    'default'           => 0,
                ),
                array(
                    'name'      => 'wpb_fmc_cat_hierarchical',
                    'label'     => esc_html__( 'Hierarchical', WPB_FMC_Domain ),
                    'desc'      => esc_html__( 'Default: off', WPB_FMC_Domain ),
                    'type'      => 'checkbox',
                    'default'   => 'off',
                ),
                array(
                    'name'      => 'wpb_fmc_cat_hide_empty',
                    'label'     => esc_html__( 'Hide Empty', WPB_FMC_Domain ),
                    'desc'      => esc_html__( 'Default: On', WPB_FMC_Domain ),
                    'type'      => 'checkbox',
                    'default'   => 'on',
                ),
                array(
                    'name'      => 'wpb_fmc_cat_show_count',
                    'label'     => esc_html__( 'Show Count', WPB_FMC_Domain ),
                    'desc'      => esc_html__( 'Default: On', WPB_FMC_Domain ),
                    'type'      => 'checkbox',
                    'default'   => 'on',
                ),
                array(
                    'name'              => 'wpb_fmc_cat_include',
                    'label'             => esc_html__( 'Category Include', WPB_FMC_Domain ),
                    'desc'              => esc_html__( 'Comma Separates category ids', WPB_FMC_Domain ),
                    'placeholder'       => esc_html__( '35,76,89', WPB_FMC_Domain ),
                    'type'              => 'text',
                    'sanitize_callback' => 'sanitize_text_field'
                ),
                array(
                    'name'              => 'wpb_fmc_cat_exclude',
                    'label'             => esc_html__( 'Category Exclude', WPB_FMC_Domain ),
                    'desc'              => esc_html__( 'Comma Separates category ids', WPB_FMC_Domain ),
                    'placeholder'       => esc_html__( '35,76,89', WPB_FMC_Domain ),
                    'type'              => 'text',
                    'sanitize_callback' => 'sanitize_text_field'
                ),
                array(
                    'name'    => 'wpb_fmc_cat_position',
                    'label'   => esc_html__( 'Select Category Position', WPB_FMC_Domain ),
                    'desc'    => esc_html__( 'Select floating category position. Default left.', WPB_FMC_Domain ),
                    'type'    => 'select',
                    'default' => 'left',
                    'options' => array(
                        'left'      => esc_html__( 'Left', WPB_FMC_Domain ),
                        'right'     => esc_html__( 'Right', WPB_FMC_Domain ),
                    )
                ),
                array(
                    'name'    => 'wpb_fmc_category_skin',
                    'label'   => esc_html__( 'Select Category Skin', WPB_FMC_Domain ),
                    'desc'    => esc_html__( 'Select a skin for floating category. ( Free version has only two skins. )', WPB_FMC_Domain ),
                    'type'    => 'select',
                    'default' => 'default',
                    'options' => apply_filters( 'wpb_fmc_skins', array(
                        'default'           => esc_html__( 'Default', WPB_FMC_Domain ),
                        'floating_icons'    => esc_html__( 'Floating Icons', WPB_FMC_Domain ),
                    )),
                ),
            ),
            'wpb_fmc_style_settings' => array(
                array(
                    'name'      => 'wpb_fmc_disable_phone',
                    'label'     => esc_html__( 'Disable on Phone', WPB_FMC_Domain ),
                    'desc'      => esc_html__( 'Enable or disable floating menu/category on phone. Default: disable on phone', WPB_FMC_Domain ),
                    'type'      => 'checkbox',
                    'default'   => 'on',
                ),
                array(
                    'name'    => 'primary_color',
                    'label'   => esc_html__( 'Primary Color', WPB_FMC_Domain ),
                    'desc'    => esc_html__( 'Choose primary color.', WPB_FMC_Domain ),
                    'type'    => 'color',
                    'default' => '#ff1053'
                ),
                array(
                    'name'    => 'secondary_color',
                    'label'   => esc_html__( 'Secondary Color', WPB_FMC_Domain ),
                    'desc'    => esc_html__( 'Choose secondary color.', WPB_FMC_Domain ),
                    'type'    => 'color',
                    'default' => '#db0843'
                ),
            )
        );

        return $settings_fields;
    }

    function plugin_page() {
        echo '<div class="wrap">';

        $this->settings_api->show_navigation();
        $this->settings_api->show_forms();

        echo '</div>';
    }

    /**
     * Get all the pages
     *
     * @return array page names with key value pairs
     */
    function get_pages() {
        $pages = get_pages();
        $pages_options = array();
        if ( $pages ) {
            foreach ($pages as $page) {
                $pages_options[$page->ID] = $page->post_title;
            }
        }

        return $pages_options;
    }

}
endif;

new WPB_FMC_WeDevs_Settings_API_Init();