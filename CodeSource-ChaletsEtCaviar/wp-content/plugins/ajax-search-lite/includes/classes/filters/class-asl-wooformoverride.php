<?php
if (!defined('ABSPATH')) die('-1');

if (!class_exists("WD_ASL_WooFormOverride_Filter")) {
    /**
     * Class WD_ASL_WooFormOverride_Filter
     *
     * Handles the default search form layout override for WooCommerce
     *
     * @class         WD_ASL_WooFormOverride_Filter
     * @version       1.0
     * @package       AjaxSearchLite/Classes/Filters
     * @category      Class
     * @author        Ernest Marcinko
     */
    class WD_ASL_WooFormOverride_Filter extends WD_ASL_Filter_Abstract {

        public function handle( $form = "" ) {
            $inst = wd_asl()->instances->get(0);

            if (  $inst['data']['override_woo_search_form'] )
                return do_shortcode("[wpdreams_ajaxsearchlite]");

            return $form;
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