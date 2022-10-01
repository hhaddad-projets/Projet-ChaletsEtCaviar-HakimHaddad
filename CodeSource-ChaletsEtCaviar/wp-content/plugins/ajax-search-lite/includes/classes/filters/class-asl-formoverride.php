<?php
if (!defined('ABSPATH')) die('-1');

if (!class_exists("WD_ASL_FormOverride_Filter")) {
    /**
     * Class WD_ASL_FormOverride_Filter
     *
     * Handles the default search form layout override
     *
     * @class         WD_ASL_FormOverride_Filter
     * @version       1.0
     * @package       AjaxSearchPro/Classes/Filters
     * @category      Class
     * @author        Ernest Marcinko
     */
    class WD_ASL_FormOverride_Filter extends WD_ASL_Filter_Abstract {

        public function handle( $form = "" ) {
            if(isset($_SERVER['HTTP_USER_AGENT']) && preg_match('/(?i)msie [6-8]/',$_SERVER['HTTP_USER_AGENT']) ) {
                $comp_options = wd_asl()->o['asl_compatibility'];
                if ( $comp_options['old_browser_compatibility'] == 1 ) {
                    return $form;
                }
            }

            $inst = wd_asl()->instances->get(0);

            if (  $inst['data']['override_search_form'] )
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