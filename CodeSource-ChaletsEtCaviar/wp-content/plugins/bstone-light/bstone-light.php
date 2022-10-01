<?php
/**
 * Plugin Name: Bstone Light
 * Plugin URI: https://wpbstone.com/
 * Description: Add extra features like widgets and import/export.
 * Version: 1.0.3
 * Author: Stack Themes
 * Author URI: https://stackthemes.net/
 * Text Domain: bstone-light
 *
 * @package Bstone Light
 */

 // Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * Set constants.
 */
if ( ! defined( 'BSTONE_LIGHT_NAME' ) ) {
	define( 'BSTONE_LIGHT_NAME', __( 'Bstone Light', 'bstone-light' ) );
}

if ( ! defined( 'BSTONE_LIGHT_VER' ) ) {
	define( 'BSTONE_LIGHT_VER', '1.0.3' );
}

if ( ! defined( 'BSTONE_LIGHT_FILE' ) ) {
	define( 'BSTONE_LIGHT_FILE', __FILE__ );
}

if ( ! defined( 'BSTONE_LIGHT_BASE' ) ) {
	define( 'BSTONE_LIGHT_BASE', plugin_basename( BSTONE_LIGHT_FILE ) );
}

if ( ! defined( 'BSTONE_LIGHT_DIR' ) ) {
	define( 'BSTONE_LIGHT_DIR', plugin_dir_path( BSTONE_LIGHT_FILE ) );
}

if ( ! defined( 'BSTONE_LIGHT_URI' ) ) {
	define( 'BSTONE_LIGHT_URI', plugins_url( '/', BSTONE_LIGHT_FILE ) );
}

if ( ! defined( 'BSTONE_LIGHT_LINK' ) ) {
	define( 'BSTONE_LIGHT_LINK', 'https://www.wpbstone.com/' );
}

$bstone_theme = wp_get_theme();

if ( ! function_exists( 'bstone_light_setup' ) && ( 'Bstone' == $bstone_theme->name || 'Bstone' == $bstone_theme->parent_theme ) ) :

	/**
	 * Bstone Light Setup
	 *
	 * @Bstone 1.0.0
	 */
	function bstone_light_setup() {
        
		require_once BSTONE_LIGHT_DIR . 'inc/bstone-light-setup.php';
	}

	add_action( 'plugins_loaded', 'bstone_light_setup' );

endif;