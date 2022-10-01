<?php
if (!defined('ABSPATH')) die('-1');

if (!class_exists("WD_ASL_Search_Handler")) {
    /**
     * Class WD_ASL_Search_Handler
     *
     * This is the ajax search handler class
     *
     * @class         WD_ASL_Search_Handler
     * @version       1.0
     * @package       AjaxSearchLite/Classes/Ajax
     * @category      Class
     * @author        Ernest Marcinko
     */
    class WD_ASL_Search_Handler extends WD_ASL_Handler_Abstract {


        /**
         * Oversees and handles the search request
         *
         * @param bool $dontGroup
         * @return array|mixed|void
         */
        public function handle($dontGroup = false) {

            $s = $_POST['aslp'];
            $s = apply_filters('asl_search_phrase_before_cleaning', $s);

            $s = stripcslashes($s);
            $s = trim($s);
            $s = preg_replace('/\s+/', ' ', $s);

            $s = apply_filters('asl_search_phrase_after_cleaning', $s);

            $id = 0;
            $instance = wd_asl()->instances->get($id);
            $sd = &$instance['data'];

            $searchController = new asl_searchController(array(
                "phrase"    => $s,
                "id"        => $id,
                "instance"  => $instance
            ));

            $results = $searchController->search();

            if (count($results) <= 0 && $sd['kw_suggestions'])
                $results = $searchController->kwSuggestions();

            do_action('asl_after_search', $s, $results, $id);

            // Override from hooks
            if (isset($_POST['asl_get_as_array'])) {
                return $results;
            }

            // Generate the results here
            $html_results = asl_generate_html_results( $results, $sd );

            /* Clear output buffer, possible warnings */
            print "!!ASLSTART!!";
            print_r($html_results);
            print "!!ASLEND!!";
            die();
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