<?php
/**
 * Register and load Bstone widgets
 *
 * @package Bstone Light
 * 
 * @since   Bstone Light 1.0.0
 */

function bstone_light_load_widgets() {
    register_widget( 'bstone_light_widget_search' );
    register_widget( 'bstone_light_widget_latest_articles' );
    register_widget( 'bstone_light_widget_social_icons' );
    if( class_exists( 'WooCommerce' ) ) {
        register_widget( 'bstone_light_widget_woo_mini_cart' );
    }
}

add_action( 'widgets_init', 'bstone_light_load_widgets' );