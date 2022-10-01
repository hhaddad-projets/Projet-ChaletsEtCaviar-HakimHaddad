<?php
/**
 * Import Demo Sites - if one-click-demo-import plugin is activated
 *
 * @package Bstone Light
 * 
 * @since   Bstone Light 1.0.0
 */

if( isset( $_REQUEST ) ) {
	if( isset( $_REQUEST['page'] ) && 'bstone-demo-import' == $_REQUEST['page'] ) {
		global $wp_filesystem;

		$wpbase_upload_dir = wp_upload_dir();

		$dir = trailingslashit( $wpbase_upload_dir['basedir'] ) . 'bstone'. DIRECTORY_SEPARATOR; // Set storage directory path

		if ( empty( $wp_filesystem ) ) {
			require_once( ABSPATH .'/wp-admin/includes/file.php' );
			WP_Filesystem();
		}

		if ( $wp_filesystem ) {

			$wp_filesystem->mkdir( $dir ); // Make a new folder 'bstone' for storing our file if not created already.

			$existing_file = $dir . 'bstone-demo-data.json';

			$existing_json = $wp_filesystem->get_contents( $existing_file );

			$api_url = apply_filters( 'bstone_demo_api_url', 'https://wpbstone.com/wp-json/wp/v2' );

			$bstone_demo_data_json = file_get_contents( $api_url );
			
			if ( $existing_json !== $bstone_demo_data_json ) {
				$wp_filesystem->put_contents( $existing_file, $bstone_demo_data_json, FS_CHMOD_FILE );
			}
		}
	}
}

 /**
  * Import Bstone demo data files
  */
function bstone_load_demo_import_files() {

	// Demos data array
	$bstone_demo_data_array = array();
	
	/**
	 * Import Notice
	 */
	$bst_in_default = __( 'Before you begin, make sure "<a href="https://wordpress.org/plugins/elementor/" target="_blank">Elementor</a>" plugin is installed and activated.', 'bstone-light' );
	$bst_in_elementor_contact = __( 'Before you begin, make sure "<a href="https://wordpress.org/plugins/elementor/" target="_blank">Elementor</a>" and "<a href="https://wordpress.org/plugins/contact-form-7/" target="_blank">Contact Form 7</a>" plugins are installed and activated.', 'bstone-light' );

	$wpbase_upload_dir = wp_upload_dir();
	$dir = trailingslashit( $wpbase_upload_dir['basedir'] ) . 'bstone'. DIRECTORY_SEPARATOR;
	$bst_demo_file = $dir . 'bstone-demo-data.json';
	
	if ( file_exists( $bst_demo_file ) ) {

		$bstone_demo_data_json = file_get_contents( $bst_demo_file );
		$bston_get_demo_data = json_decode( $bstone_demo_data_json );

		if( is_array( $bston_get_demo_data->demos ) ) {

			foreach ($bston_get_demo_data->demos as $demo) {

				$demo_notice = __( 'Before you begin, make sure all the required plugins are activated.', 'bstone-light' );

				if( 'default' == $demo->import_notice ) {
					$demo_notice = $bst_in_default;

				} else if( 'elementor_contact' == $demo->import_notice ) {
					$demo_notice = $bst_in_elementor_contact;
				}

				$bst_demo_array = array(
					'import_file_name'           => $demo->name,
					'categories'                 => $demo->categories,
					'import_file_url'            => $demo->import_file_url,
					'import_widget_file_url'     => $demo->widget_file_url,
					'import_customizer_file_url' => $demo->customizer_file_url,
					'import_preview_image_url'   => $demo->preview_image_url,
					'import_notice'              => $demo_notice,
					'preview_url'                => $demo->preview_url,
				);

				array_push( $bstone_demo_data_array, $bst_demo_array );
			}

		}
	}

	return $bstone_demo_data_array;
}

if( is_admin() ) {
	add_filter( 'pt-ocdi/import_files', 'bstone_load_demo_import_files' );
}

/**
 * Set menu, home page and posts page
 */
function bstone_after_demo_import( $selected_import ) {

	$wpbase_upload_dir = wp_upload_dir();
	$dir = trailingslashit( $wpbase_upload_dir['basedir'] ) . 'bstone'. DIRECTORY_SEPARATOR;
	$bst_demo_file = $dir . 'bstone-demo-data.json';

	$bstone_demo_data_json = file_get_contents( $bst_demo_file );
	$bston_get_demo_data = json_decode( $bstone_demo_data_json );

	foreach ($bston_get_demo_data->demo_settings as $demo_settings) {

		if ( $demo_settings->demo_name === $selected_import['import_file_name'] ) {

			// Assign menus to their locations.
			$main_menu = get_term_by( 'name', $demo_settings->menu_name, 'nav_menu' );

			set_theme_mod( 'nav_menu_locations', array(
					'primary' => $main_menu->term_id,
				)
			);
		}

		// Assign front page and posts page (blog page).
		$front_page_id = get_page_by_title( $demo_settings->front_page );
		$blog_page_id  = get_page_by_title( $demo_settings->posts_page );

		if ( isset( $front_page_id->ID ) ) {
			update_option( 'page_on_front', $front_page_id->ID );
			update_option( 'show_on_front', 'page' );
		}

		if ( isset( $blog_page_id->ID ) ) {
			update_option( 'page_for_posts', $blog_page_id->ID );
		}
	}
}
add_action( 'pt-ocdi/after_import', 'bstone_after_demo_import' );

/**
 * Change demo page title and slug
 */
function bstone_demo_import_plugin_page_setup( $default_settings ) {
	$default_settings['parent_slug'] = 'themes.php';
	$default_settings['page_title']  = esc_html__( 'Bstone Demo Import' , 'bstone-light' );
	$default_settings['menu_title']  = esc_html__( 'Bstone Demo Import' , 'bstone-light' );
	$default_settings['capability']  = 'import';
	$default_settings['menu_slug']   = 'bstone-demo-import';

	return $default_settings;
}
add_filter( 'pt-ocdi/plugin_page_setup', 'bstone_demo_import_plugin_page_setup' );

/**
 * Disable the ProteusThemes branding notice after successful demo import
 */
add_filter( 'pt-ocdi/disable_pt_branding', '__return_true' );

/**
 * Bstone demo import page text
 */
function bstone_demo_import_plugin_intro_text( $default_text ) {
	$default_text .= '<hr />';

	return $default_text;
}
add_filter( 'pt-ocdi/plugin_intro_text', 'bstone_demo_import_plugin_intro_text' );



