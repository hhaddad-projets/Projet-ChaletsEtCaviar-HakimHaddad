<?php
/**
 * Functions
 *
 * @package Bstone Light
 * 
 * @since   1.0.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * Get customizer link
 */
if( ! function_exists( 'bstone_get_customizer_link' ) ) {
    function bstone_get_customizer_link( $location, $type ) {
        
        if( 'panel' == $type ) {

            $query['autofocus[panel]'] = $location;
            $panel_link = add_query_arg( $query, admin_url( 'customize.php' ) );

            return $panel_link;

        } else if ( 'section' == $type ) {

            $query['autofocus[section]'] = $location;
            $section_link = add_query_arg( $query, admin_url( 'customize.php' ) );

            return $section_link;
        } else if ( 'control' == $type ) {

            $query['autofocus[control]'] = $location;
            $section_link = add_query_arg( $query, admin_url( 'customize.php' ) );

            return $section_link;
        }
    }
}

/**
 * Required Plugin Activate
 */
function bstone_required_plugin_activate() {

    if ( ! current_user_can( 'install_plugins' ) || ! isset( $_POST['init'] ) || ! $_POST['init'] ) {
        wp_send_json_error(
            array(
                'success' => false,
                'message' => __( 'No plugin specified', 'bstone-light' ),
            )
        );
    }

    $plugin_init = ( isset( $_POST['init'] ) ) ? esc_attr( $_POST['init'] ) : '';

    $activate = activate_plugin( $plugin_init, '', false, true );

    if ( is_wp_error( $activate ) ) {
        wp_send_json_error(
            array(
                'success' => false,
                'message' => $activate->get_error_message(),
            )
        );
    }

    wp_send_json_success(
        array(
            'success' => true,
            'message' => __( 'Plugin Successfully Activated', 'bstone-light' ),
        )
    );

}
add_action( 'wp_ajax_one-click-demo-import-plugin-activate', 'bstone_required_plugin_activate' );

/**
 * Get Bstone Light Options
 */
function bstone_light_get_option( $id = '' ) {
	return Bstone_Light_Customizer_Options::get_bstone_light_option( $id );
}

/**
 * Disable Customizer Sections - Based on settings in options page
 */
function bst_modify_customize_sections() {     
    global $wp_customize;

    $bst_customizer_sections = array(
        'panel-layout' => 'panel',
        'panel-colors' => 'panel',
        'panel-typography' => 'panel',
        'panel-spacing' => 'panel',
        'section-posts-slider' => 'section',
        'section-pagination-settings' => 'section',
        'section-forms-settings' => 'section',
        'section-scroll-top-settings' => 'section'
    );

    foreach( $bst_customizer_sections as $section => $type ):
        $settings_val = bstone_light_get_option( 'bst-enable-' . $section );

        if( null != $settings_val ) {
            if( 'panel' == $type ) {

                $wp_customize->remove_panel( $section );

            } else if( 'section' == $type ) {

                $wp_customize->remove_section( $section );
            }
        }
    endforeach;
} 
    
add_action( 'customize_register', 'bst_modify_customize_sections', 11 );