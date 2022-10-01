<?php
/**
 * Enqueue scripts
 * 
 * @package Bstone Light
 * 
 * @since   Bstone Light 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Bstone_Light_Enqueue_Scripts' ) ) {
	class Bstone_Light_Enqueue_Scripts {
        /**
		 * Class styles.
		 *
		 * @access public
		 * @var $styles Enqueued styles.
		 */
		public static $styles;

		/**
		 * Class scripts.
		 *
		 * @access public
		 * @var $scripts Enqueued scripts.
		 */
        public static $scripts;
        
        /**
		 * Constructor
		 */
		public function __construct() {

			add_action( 'wp_enqueue_scripts',        array( $this, 'enqueue_scripts' ), 5 );
			add_action( 'wp_enqueue_scripts',        array( $this, 'enque_jquery_script' ), 5 );
        }

		/**
		 * Enqueue jQuery
		 */
		public function enque_jquery_script() {
			wp_enqueue_script("jquery");
		}
        
        /**
		 * List of all assets.
		 *
		 * @return array assets array.
		 */
		public static function plugin_assets() {
            $default_assets = array(

                // handle => location ( in /assets/js/ ) ( without .js ext).
                'js' => array(
                    'bstone-light-js' => 'bstone-light-script',
                ),

                // handle => location ( in /assets/css/ ) ( without .css ext).
                'css' => array(
                    'bstone-light-style' => 'bstone-light-style',
                ),
            );

            return apply_filters( 'bstone_light_assets', $default_assets );
        }

        /**
		 * Enqueue Scripts
		 */
		public function enqueue_scripts() {

			/* Directory and Extension */
			$file_prefix = ( SCRIPT_DEBUG ) ? '' : '.min';
			$dir_name    = ( SCRIPT_DEBUG ) ? 'unminified' : 'minified';

			$js_uri  = BSTONE_LIGHT_URI . 'inc/assets/js/' . $dir_name . '/';
			$css_uri = BSTONE_LIGHT_URI . 'inc/assets/css/' . $dir_name . '/';

			// All assets.
			$all_assets = self::plugin_assets();
			$styles     = $all_assets['css'];
			$scripts    = $all_assets['js'];

			// Register & Enqueue Styles.
			foreach ( $styles as $key => $style ) {

				// Generate CSS URL.
				$css_file = $css_uri . $style . $file_prefix . '.css';

				// Register.
				wp_register_style( $key, $css_file, array(), BSTONE_LIGHT_VER, 'all' );

				// Enqueue.
				wp_enqueue_style( $key );

				// RTL support.
				wp_style_add_data( $key, 'rtl', 'replace' );

			}

			// Register & Enqueue Scripts.
			foreach ( $scripts as $key => $script ) {

				// Register.
				wp_register_script( $key, $js_uri . $script . $file_prefix . '.js', array(), BSTONE_LIGHT_VER, true );

				// Enqueue.
				wp_enqueue_script( $key );

            }
        }
    }

    new Bstone_Light_Enqueue_Scripts();
}