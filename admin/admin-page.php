<?php

/**
 * WPB Floating Menu or Categories 
 */

wp_enqueue_style( 'wpb_fmc_admin_css' );
$plugin_data = get_plugin_data( WPB_FMC_PLUGIN_DIR_FILE );
$version 	 = $plugin_data['Version'];
?>

<div class="wrap wpb-about-wrap">
	<h2 class="nav-tab-wrapper">
        <a href="#wpb_fmc_welcome" class="nav-tab" id="wpb_fmc_welcome-tab"><?php esc_html_e( 'Welcome', WPB_FMC_Domain ) ?></a>
        <a href="#wpb_fmc_use" class="nav-tab" id="wpb_fmc_use-tab"><?php esc_html_e( 'How To Use', WPB_FMC_Domain ) ?></a>
        <a href="#wpb_fmc_shortcode" class="nav-tab" id="wpb_fmc_use-tab"><?php esc_html_e( 'ShortCodes', WPB_FMC_Domain ) ?></a>
	</h2>
	<div class="metabox-holder">
		<div id="wpb_fmc_welcome" class="group">
			<h1><?php esc_html_e( 'WPB Floating Menu or Categories - ' . esc_html( $version ), WPB_FMC_Domain );?></h1>
			<div class="wpb-about-text">
				<?php esc_html_e( 'This plugin helps you by showing floating menu or categories with icons in your site. It comes with a nice and clean design. All the customization options are available in the plugin settings.', WPB_FMC_Domain );?>
			</div>
		</div>

        <div id="wpb_fmc_use" class="group">
            <h3><?php esc_html_e( 'How to use:', WPB_FMC_Domain );?></h3>
            <ol>
                <li>Install it as a regular WordPress plugin</li>
                <li>After installing the plugin, go to this plugin <a href="<?php echo esc_url( get_admin_url() . 'admin.php?page=wpb-floating-menu-or-categories-settings' ); ?>">settings</a> and configure your floating menu or category.</li>
                <li>You can use this plugin’s ShortCodes to show the floating menu or categories on a specific page or post on your site.</li>
            </ol>
        </div>

        <div id="wpb_fmc_shortcode" class="group">
            <h3><?php esc_html_e( 'ShortCodes:', WPB_FMC_Domain );?></h3>
            <ol>
                <li><b>Floating Menu </b><input type="text" value='[wpb-fmc-floating-menu]'></li>
                <li><b>Floating Category </b><input type="text" value='[wpb-fmc-floating-category]'></li>
            </ol>
        </div>  
	</div>	
</div>

<div class="clear"></div>

<div class="wpb_wpbean_socials">
    <h4><?php esc_html_e( 'For getting updates of our plugins, features update, WordPress new trend, New web technology etc. Follows Us.', WPB_FMC_Domain );?></h4>
    <a href="https://twitter.com/wpbean" title="Follow us on Twitter" class="wpb_twitter" target="_blank"><?php esc_html_e( 'Follow Us On Twitter', WPB_FMC_Domain );?></a>
    <a href="https://plus.google.com/u/0/+WpBean/posts" title="Follow us on Google+" class="wpb_googleplus" target="_blank"><?php esc_html_e( 'Follow Us On Google Plus', WPB_FMC_Domain );?></a>
    <a href="https://www.facebook.com/wpbean" title="Follow us on Facebook" class="wpb_facebook" target="_blank"><?php esc_html_e( 'Like Us On FaceBook', WPB_FMC_Domain );?></a>
    <a href="https://www.youtube.com/user/wpbean/videos" title="Follow us on Youtube" class="wpb_youtube" target="_blank"><?php esc_html_e( 'Subscribe Us on YouTube', WPB_FMC_Domain );?></a>
    <a href="https://wpbean.com/support/" title="Get Support" class="wpb_support" target="_blank"><?php esc_html_e( 'Get Support', WPB_FMC_Domain );?></a>
    <a href="http://docs.wpbean.com/docs/wpb-floating-menu-or-categories/installing/" title="Documentation" class="wpb_documentation" target="_blank"><?php esc_html_e( 'Online Documentation', WPB_FMC_Domain );?></a>
</div>

<script>
    jQuery(document).ready(function($) {

        // Switches option sections
        $('.group').hide();
        var activetab = '';
        if (typeof(localStorage) != 'undefined' ) {
            activetab = localStorage.getItem("activetab");
        }
        if (activetab != '' && $(activetab).length ) {
            $(activetab).fadeIn();
        } else {
            $('.group:first').fadeIn();
        }
        $('.group .collapsed').each(function(){
            $(this).find('input:checked').parent().parent().parent().nextAll().each(
            function(){
                if ($(this).hasClass('last')) {
                    $(this).removeClass('hidden');
                    return false;
                }
                $(this).filter('.hidden').removeClass('hidden');
            });
        });

        if (activetab != '' && $(activetab + '-tab').length ) {
            $(activetab + '-tab').addClass('nav-tab-active');
        }
        else {
            $('.nav-tab-wrapper a:first').addClass('nav-tab-active');
        }
        $('.nav-tab-wrapper a').click(function(evt) {
            $('.nav-tab-wrapper a').removeClass('nav-tab-active');
            $(this).addClass('nav-tab-active').blur();
            var clicked_group = $(this).attr('href');
            if (typeof(localStorage) != 'undefined' ) {
                localStorage.setItem("activetab", $(this).attr('href'));
            }
            $('.group').hide();
            $(clicked_group).fadeIn();
            evt.preventDefault();
        });

        $(".wpb-about-wrap input[type='text']").on("click", function () {
		   $(this).select();
		});
	});
</script>
