<?php
/**
 * Create An Options Panel
 * 
 * @package Bstone Light
 * 
 * @since   1.0.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Start Class
if ( ! class_exists( 'Bstone_Light_Customizer_Options' ) ) {

	class Bstone_Light_Customizer_Options {

		/**
		 * Start things up
		 *
		 * @since 1.0.0
		 */
		public function __construct() {

			// We only need to register the admin panel on the back-end
			if ( is_admin() ) {
				add_action( 'admin_init', array( 'Bstone_Light_Customizer_Options', 'register_settings' ) );
			}

		}

		/**
		 * Returns all options
		 *
		 * @since 1.0.0
		 */
		public static function get_bstone_light_options() {
			return get_option( 'bstone_light_options' );
		}

		/**
		 * Returns single option
		 *
		 * @since 1.0.0
		 */
		public static function get_bstone_light_option( $id ) {
			$options = self::get_bstone_light_options();
			if ( isset( $options[$id] ) ) {
				return $options[$id];
			}
		}

		/**
		 * Register a setting and its sanitization callback.
		 *
		 * We are only registering 1 setting so we can store all options in a single option as
		 * an array. You could, however, register a new setting for each option
		 *
		 * @since 1.0.0
		 */
		public static function register_settings() {
			register_setting( 'bstone_light_options', 'bstone_light_options', array( 'Bstone_Light_Customizer_Options', 'sanitize' ) );
		}

		/**
		 * Sanitization callback
		 *
		 * @since 1.0.0
		 */
		public static function sanitize( $options ) {

			// If we have options lets sanitize them
			if ( $options ) {

				// Layout Panel
				if ( ! empty( $options['bst-enable-panel-layout'] ) ) {
					$options['bst-enable-panel-layout'] = 'on';
				} else {
					unset( $options['bst-enable-panel-layout'] );
				}

				// Colors Panel
				if ( ! empty( $options['bst-enable-panel-colors'] ) ) {
					$options['bst-enable-panel-colors'] = 'on';
				} else {
					unset( $options['bst-enable-panel-colors'] );
				}

				// Typography Panel
				if ( ! empty( $options['bst-enable-panel-typography'] ) ) {
					$options['bst-enable-panel-typography'] = 'on';
				} else {
					unset( $options['bst-enable-panel-typography'] );
				}

				// Spacing Panel
				if ( ! empty( $options['bst-enable-panel-spacing'] ) ) {
					$options['bst-enable-panel-spacing'] = 'on';
				} else {
					unset( $options['bst-enable-panel-spacing'] );
				}

				// Posts Slider Section
				if ( ! empty( $options['bst-enable-section-posts-slider'] ) ) {
					$options['bst-enable-section-posts-slider'] = 'on';
				} else {
					unset( $options['bst-enable-section-posts-slider'] );
				}

				// Pagination Section
				if ( ! empty( $options['bst-enable-section-pagination-settings'] ) ) {
					$options['bst-enable-section-pagination-settings'] = 'on';
				} else {
					unset( $options['bst-enable-section-pagination-settings'] );
				}

				// Forms Section
				if ( ! empty( $options['bst-enable-section-forms-settings'] ) ) {
					$options['bst-enable-section-forms-settings'] = 'on';
				} else {
					unset( $options['bst-enable-section-forms-settings'] );
				}

				// Scroll to Top Section
				if ( ! empty( $options['bst-enable-section-scroll-top-settings'] ) ) {
					$options['bst-enable-section-scroll-top-settings'] = 'on';
				} else {
					unset( $options['bst-enable-section-scroll-top-settings'] );
				}

			}

			// Return sanitized options
			return $options;

		}

		/**
		 * Settings page output
		 *
		 * @since 1.0.0
		 */
		public static function get_bst_options_form() { ?>

			<div class="bst-enable-customizer-sections">

				<form method="post" action="options.php">

					<?php settings_fields( 'bstone_light_options' ); ?>

					<div style="margin: 0px -0.5%;">

                        <div class="options-column-wrap">
                            <label for="bst-enable-panel-layout" class="column-name clear">
                                <h3 class="title"><?php echo __( 'Layout Panel', 'bstone-light' ); ?></h3>
                                <?php $value = self::get_bstone_light_option( 'bst-enable-panel-layout' ); ?>
                                <input type="checkbox" name="bstone_light_options[bst-enable-panel-layout]" value="on" id="bst-enable-panel-layout" <?php checked( $value, 'on' ); ?>>
                                <div class="clear"></div>
                            </label>
                        </div>
                        
                        <div class="options-column-wrap">
                            <label for="bst-enable-panel-colors" class="column-name clear">
                                <h3 class="title"><?php echo __( 'Colors Panel', 'bstone-light' ); ?></h3>
                                <?php $value = self::get_bstone_light_option( 'bst-enable-panel-colors' ); ?>
                                <input type="checkbox" name="bstone_light_options[bst-enable-panel-colors]" value="on" id="bst-enable-panel-colors" <?php checked( $value, 'on' ); ?> />
                                <div class="clear"></div>
                            </label>
                        </div>
                        
                        <div class="options-column-wrap">
                            <label for="bst-enable-panel-typography" class="column-name clear">
                                <h3 class="title"><?php echo __( 'Typography Panel', 'bstone-light' ); ?></h3>
                                <?php $value = self::get_bstone_light_option( 'bst-enable-panel-typography' ); ?>
                                <input type="checkbox" name="bstone_light_options[bst-enable-panel-typography]" value="on" id="bst-enable-panel-typography" <?php checked( $value, 'on' ); ?> />
                                <div class="clear"></div>
                            </label>
                        </div>
                        
                        <div class="options-column-wrap">
                            <label for="bst-enable-panel-spacing" class="column-name clear">
                                <h3 class="title"><?php echo __( 'Spacing Panel', 'bstone-light' ); ?></h3>
                                <?php $value = self::get_bstone_light_option( 'bst-enable-panel-spacing' ); ?>
                                <input type="checkbox" name="bstone_light_options[bst-enable-panel-spacing]" value="on" id="bst-enable-panel-spacing" <?php checked( $value, 'on' ); ?> />
                                <div class="clear"></div>
                            </label>
                        </div>
                        
                        <div class="options-column-wrap">
                            <label for="bst-enable-section-posts-slider" class="column-name clear">
                                <h3 class="title"><?php echo __( 'Posts Slider Section', 'bstone-light' ); ?></h3>
                                <?php $value = self::get_bstone_light_option( 'bst-enable-section-posts-slider' ); ?>
                                <input type="checkbox" name="bstone_light_options[bst-enable-section-posts-slider]" value="on" id="bst-enable-section-posts-slider" <?php checked( $value, 'on' ); ?> />
                                <div class="clear"></div>
                            </label>
                        </div>
                        
                        <div class="options-column-wrap">
                            <label for="bst-enable-section-pagination-settings" class="column-name clear">
                                <h3 class="title"><?php echo __( 'Pagination Section', 'bstone-light' ); ?></h3>
                                <?php $value = self::get_bstone_light_option( 'bst-enable-section-pagination-settings' ); ?>
                                <input type="checkbox" name="bstone_light_options[bst-enable-section-pagination-settings]" value="on" id="bst-enable-section-pagination-settings" <?php checked( $value, 'on' ); ?> />
                                <div class="clear"></div>
                            </label>
                        </div>
                        
                        <div class="options-column-wrap">
                            <label for="bst-enable-section-forms-settings" class="column-name clear">
                                <h3 class="title"><?php echo __( 'Forms Section', 'bstone-light' ); ?></h3>
                                <?php $value = self::get_bstone_light_option( 'bst-enable-section-forms-settings' ); ?>
                                <input type="checkbox" name="bstone_light_options[bst-enable-section-forms-settings]" value="on" id="bst-enable-section-forms-settings" <?php checked( $value, 'on' ); ?> />
                                <div class="clear"></div>
                            </label>
                        </div>
                        
                        <div class="options-column-wrap">
                            <label for="bst-enable-section-scroll-top-settings" class="column-name clear">
                                <h3 class="title"><?php echo __( 'Scroll to Top Section', 'bstone-light' ); ?></h3>
                                <?php $value = self::get_bstone_light_option( 'bst-enable-section-scroll-top-settings' ); ?>
                                <input type="checkbox" name="bstone_light_options[bst-enable-section-scroll-top-settings]" value="on" id="bst-enable-section-scroll-top-settings" <?php checked( $value, 'on' ); ?> />
                                <div class="clear"></div>
                            </label>
                        </div>

                        <div class="clear"></div>
                    </div>

					<div style="margin-top: -15px; margin-bottom: -1em;">
						<?php submit_button(); ?>
					</div>

				</form>

			</div><!-- .wrap -->
		<?php }

	}
}
new Bstone_Light_Customizer_Options();