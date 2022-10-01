<?php
/**
 * Bstone Light Strings
 *
 * @package Bstone Light
 * 
 * @since   1.0.0
 */

/**
 * Default Strings
 */
if ( ! function_exists( 'bstone_light_default_strings' ) ) {

	/**
	 * Default Strings
	 *
	 * @since 1.0.0
	 * @param  string  $key  String key.
	 * @param  boolean $echo Print string.
	 * @return mixed   Return string or nothing.
	 */
	function bstone_light_default_strings( $key, $echo = true ) {

		$defaults = array(

			// Search Widget.
			'search-form-screen-reader'	=> __( 'Search', 'bstone-light' ),
			'search-form-scrren-title'	=> __( 'Type keyword and press enter', 'bstone-light' ),
			'search-form-close-button'	=> __( 'Close', 'bstone-light' ),
		);

		$bst_defaults = apply_filters( 'bstone_light_default_strings', $defaults );

		$output = isset( $bst_defaults[ $key ] ) ? $bst_defaults[ $key ] : '';

		/**
		 * Print or return
		 */
		if ( $echo ) {
			echo $output;
		} else {
			return $output;
		}
	}
}// End if().
