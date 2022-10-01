<?php
if (!defined('ABSPATH')) die('-1');

if (!class_exists("WD_ASL_CustomFonts_Action")) {
    /**
     * Class WD_ASL_CustomFonts_Action
     *
     * Custom fonts used in search instances.
     *
     * @class         WD_ASL_CustomFonts_Action
     * @version       1.0
     * @package       AjaxSearchLite/Classes/Actions
     * @category      Class
     * @author        Ernest Marcinko
     */
    class WD_ASL_CustomFonts_Action extends WD_ASL_Action_Abstract {

        /**
         * Importing fonts does not work correctly it appears.
         * Instead adding the links directly to the header is the best way to go.
         */
        public function handle( ) {
            $_cf = WD_ASL_Search_Shortcode::getInstance();
            $_cf->fonts();
        }

        // ------------------------------------------------------------
        //   ---------------- SINGLETON SPECIFIC --------------------
        // ------------------------------------------------------------
        public static function getInstance() {
            if ( ! ( self::$_instance instanceof self ) ) {
                self::$_instance = new self();
            }

            return self::$_instance;
        }
    }
}