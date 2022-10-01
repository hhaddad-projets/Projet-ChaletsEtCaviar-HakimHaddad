<?php
if (!defined('ABSPATH')) die('-1');

if (!class_exists("WD_ASL_Search_Shortcode")) {
    /**
     * Class WD_ASL_Search_Shortcode
     *
     * Search bar shortcode
     *
     * @class         WD_ASL_Search_Shortcode
     * @version       1.0
     * @package       AjaxSearchLite/Classes/Shortcodes
     * @category      Class
     * @author        Ernest Marcinko
     */
    class WD_ASL_Search_Shortcode extends WD_ASL_Shortcode_Abstract {

        /**
         * Overall instance count
         *
         * @var int
         */
        private static $instanceCount = 0;

        /**
         * Used in views, true if the data view is printed
         *
         * @var bool
         */
        private static $dataPrinted = false;

        /**
         * Instance count per search ID
         *
         * @var array
         */
        private static $perInstanceCount = array();

        /**
         * Does the search shortcode stuff
         *
         * @param array|null $atts
         * @return string|void
         */
        public function handle($atts) {
            $style = null;
            self::$instanceCount++;

            extract(shortcode_atts(array(
                'id' => 'something'
            ), $atts));

            if(isset($_SERVER['HTTP_USER_AGENT']) && preg_match('/(?i)msie [6-8]/',$_SERVER['HTTP_USER_AGENT']) ) {
                $com_opt = wd_asl()->o['asl_compatibility'];
                if ( $com_opt['old_browser_compatibility'] == 1 ) {
                    get_search_form(true);
                    return;
                }
            }

            $inst = wd_asl()->instances->get(0);
            $style = $inst['data'];

            // Set the "_fo" item to indicate that the non-ajax search was made via this form, and save the options there
            if (isset($_POST['p_asl_data']) || isset($_POST['np_asl_data'])) {
                $_p_data = isset($_POST['p_asl_data']) ? $_POST['p_asl_data'] : $_POST['np_asl_data'];
                parse_str($_p_data, $style['_fo']);
            }

            $settingsHidden = ((
                w_isset_def($style['show_frontend_search_settings'], 1) == 1
            ) ? false : true);

            do_action('asl_layout_before_shortcode', $id);

            $out = "";
            ob_start();
            include(ASL_PATH."includes/views/asl.shortcode.php");
            $out = ob_get_clean();

            do_action('asl_layout_after_shortcode', $id);

            return $out;
        }

        /**
         * Importing fonts does not work correctly it appears.
         * Instead adding the links directly to the header is the best way to go.
         */
        public function fonts() {
            // If custom font loading is disabled, exit
            $comp_options = wd_asl()->o['asl_compatibility'];
            if ( $comp_options['load_google_fonts'] != 1 )
                return false;

            $imports = array(
                'https://fonts.googleapis.com/css?family=Open+Sans'
            );

            $imports = apply_filters('asl_custom_fonts', $imports);

            foreach ($imports as $import) {
                $import = trim(str_replace(array("@import url(", ");", "https:", "http:"), "", $import));
                ?>
                <link href='<?php echo $import; ?>' rel='stylesheet' type='text/css'>
                <?php
            }
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