<?php
/**
 * Register Custom Controls
 *
 * @package   Bstone
 * @author    Stack Themes
 * @copyright Copyright (c) 2018, Bstone
 * @link      https://wpbstone.com/
 * @since     Bstone 1.0.0
 */

function bstone_customizer_controls( $wp_customize ) {
	/**
	 * Register controls
	 */
	$wp_customize->register_control_type( 'Bstone_Control_Sortable' );
	$wp_customize->register_control_type( 'Bstone_Control_Radio_Image' );
	$wp_customize->register_control_type( 'Bstone_Control_Slider' );
	$wp_customize->register_control_type( 'Bstone_Control_Responsive' );
	$wp_customize->register_control_type( 'Bstone_Control_Spacing' );
	$wp_customize->register_control_type( 'Bstone_Control_Divider' );
	$wp_customize->register_control_type( 'Bstone_Control_Color' );
	$wp_customize->register_control_type( 'Bstone_Control_Dimensions' );
	$wp_customize->register_control_type( 'Bstone_Control_Heading' );
	$wp_customize->register_control_type( 'Bstone_Control_Tabs' );
	$wp_customize->register_control_type( 'Bstone_Control_Responsive_Slider' );
	
	require BSTONE_THEME_DIR . 'inc/customizer/customizer-custom-controls.php';
}

add_action('customize_register', 'bstone_customizer_controls');