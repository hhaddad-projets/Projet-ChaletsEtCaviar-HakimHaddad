<?php
/**
 * Enqueue scripts in the admin
 * 
 * @package Bstone Light
 * 
 * @since   Bstone Light 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function bstone_light_css_and_js() { 
    $current_screen = get_current_screen();

    wp_enqueue_style( 'bstone_light_css', BSTONE_LIGHT_URI.'inc/assets/css/bstone-light.css' );

    if( 'appearance_page_bstone-demo-import' == $current_screen->id ) {
        wp_enqueue_script( 'bstone_light_js', BSTONE_LIGHT_URI.'inc/assets/js/bstone-light.js' );
    }
    wp_enqueue_script( 'bstone_plugin_install_js', BSTONE_LIGHT_URI.'inc/assets/js/bstone-plugins.js', array( 'jquery', 'wp-util', 'updates' ), BSTONE_LIGHT_VER );
    
    $localize = array(
        'ajaxUrl'             => admin_url( 'admin-ajax.php' ),
        'btnActivating'       => __( 'Activating Importer Plugin ', 'bstone' ) . '&hellip;',
        'bstoneSitesLink'      => admin_url( 'themes.php?page=bstone-demo-import' ),
        'bstoneSitesLinkTitle' => __( 'See Demo Sites', 'bstone' ),
    );
    wp_localize_script( 'bstone_plugin_install_js', 'bstone', apply_filters( 'bstone_theme_js_localize', $localize ) );
}

add_action('admin_enqueue_scripts', 'bstone_light_css_and_js');