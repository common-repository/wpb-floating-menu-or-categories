<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly 

/* Define class for Categories icon */
class WPB_FMC_Category_Icons{

	/* call default construtor */
	function __construct(){

		$tax = sanitize_key(@$_REQUEST['taxonomy']);
		
		/* add browse and text field to upload image and add an fontawesome icon */
		add_action( $tax . "_add_form_fields", array($this,'wpb_fmc_add_new_iconfield'), 10, 2 );
		add_action( $tax . "_edit_form_fields", array($this,'wpb_fmc_edit_iconfield'), 10, 2 );

		/* save the image or font awesome icon*/
		add_action( "edited_" .$tax, array($this,'wpb_fmc_save_iconfield'), 10, 2 );  
		add_action( "create_" . $tax, array($this,'wpb_fmc_save_iconfield'), 10, 2 );

		/* show cloumn and their respective icon and images */
		add_filter( 'manage_edit-' .$tax. '_columns', array($this,'wpb_fmc_category_column' )) ;
		add_filter( 'manage_' . $tax. '_custom_column', array($this,'wpb_fmc_category_column_data'),10,3);

	}


	/* show cloumn and theie respective icon and images */
	public function wpb_fmc_category_column($columns){
		$columns["wpb_fmc_icon"] = esc_html__("Icon", WPB_FMC_Domain);
		return $columns;
	}	

	/* show cloumn and their respective icon and images */
	public function wpb_fmc_category_column_data( $content, $column, $term_id ){	

		if ( $column === 'wpb_fmc_icon' ) {
	    	$wpb_fmc_term_meta = get_option( "taxonomy_$term_id" );

	    	if( is_array($wpb_fmc_term_meta) && array_key_exists('wpb_fmc_cat_icons', $wpb_fmc_term_meta) ){
	    		$content = '<i class="fa-2x '. $wpb_fmc_term_meta['wpb_fmc_cat_icons'] .'"></i>';
	    	}
	    }

	    return $content;
	}

	/* show browse and font awesome icon option while add category */
	public function wpb_fmc_add_new_iconfield(){
	?>
	    <div class="form-field">
			<label for="wpb_fmc_term_meta[wpb_fmc_cat_icons]"><?php _e( 'Icon CSS Class', WPB_FMC_Domain ); ?></label>
			<input type="text" name="wpb_fmc_term_meta[wpb_fmc_cat_icons]" id="wpb_fmc_term_meta[wpb_fmc_cat_icons]" value="">
			<p><?php esc_html_e('You can get it from ', WPB_FMC_Domain) ?><a target="_blank" href="//fontawesome.com/v4.7.0/icons/"><?php esc_html_e('here', WPB_FMC_Domain); ?></a><?php esc_html_e(' e.g.', WPB_FMC_Domain);?> <b>fa fa-car</b></p>
		</div>
		
	<?php
	}

	// Edit term page
	public function wpb_fmc_edit_iconfield($term) {
	 
		// put the term ID into a variable
		$t_id 				= $term->term_id;

		// retrieve the existing value(s) for this meta field. This returns an array
		$wpb_fmc_term_meta = get_option( "taxonomy_$t_id" ); ?>

		<tr class="form-field">
			<th scope="row" valign="top"><label for="wpb_fmc_term_meta[wpb_fmc_cat_icons]"><?php _e( 'Icon CSS Class', WPB_FMC_Domain ); ?></label></th>
			<td>
				<input type="text" name="wpb_fmc_term_meta[wpb_fmc_cat_icons]" id="wpb_fmc_term_meta[wpb_fmc_cat_icons]" value="<?php echo esc_attr( $wpb_fmc_term_meta['wpb_fmc_cat_icons'] ) ? esc_attr( $wpb_fmc_term_meta['wpb_fmc_cat_icons'] ) : ''; ?>">
				<p class="description"><?php esc_html_e('You can get more FontAwesome icons from ', WPB_FMC_Domain) ?><a target="_blank" href="//fontawesome.com/v4.7.0/icons/"><?php esc_html_e('here', WPB_FMC_Domain); ?></a><?php esc_html_e(' e.g.', WPB_FMC_Domain);?> <b>fa fa-car</b></p>
			</td>
		</tr>
	<?php
	}

	// Save term 

	public function wpb_fmc_save_iconfield( $term_id ) {
		if ( isset( $_POST['wpb_fmc_term_meta'] ) ) {

			$wpb_fmc_term_meta 	= get_option( "taxonomy_$term_id" );
			$cat_keys 			= array_keys( $_POST['wpb_fmc_term_meta'] );

			foreach ( $cat_keys as $key ) {
				if ( isset ( $_POST['wpb_fmc_term_meta'][$key] ) ) {
					$wpb_fmc_term_meta[$key] = $_POST['wpb_fmc_term_meta'][$key];
				}
			}

			// Save the option array.
			update_option( "taxonomy_$term_id", $wpb_fmc_term_meta );
		}
	}

} /* end class */


/* Intialize the class */
$templ = new WPB_FMC_Category_Icons();