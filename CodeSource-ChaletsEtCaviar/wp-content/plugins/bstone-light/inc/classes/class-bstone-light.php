<?php
/**
 * Add extra features like widgets, import/export etc.
 *
 * @package Bstone Light
 * 
 * @since   Bstone Light 1.0.0
 */

if ( ! class_exists( 'Bstone_Light_Settings' ) ) {
    /**
	 * Bstone_Light_Settings initial setup
	 */
	class Bstone_Light_Settings {
        /**
		 * Instance
		 *
		 * @var $instance
		 */
        private static $instance;
        
        /**
		 * Initiator
		 *
		 * @since 1.0.0
		 * @return object
		 */
		public static function get_instance() {
			if ( ! isset( self::$instance ) ) {
				self::$instance = new self;
			}
			return self::$instance;
		}

		/**
		 * Constructor
		 */
		public function __construct() {
			add_action( 'admin_menu', array( $this, 'admin_menu_link' ), 1);
        }
        
        /**
		 * Import Demo Sites Data
		 *
		 * @since 1.0.0
		 */
		function admin_menu_link() {
            add_submenu_page(
				'themes.php',
				'Bstone Options',
				'Bstone Options',
				'edit_themes',
				'bstone',
				'get_options_page_content'
			);
		}
    }
}

/**
 * Kicking this off by calling 'get_instance()' method
 */
Bstone_Light_Settings::get_instance();