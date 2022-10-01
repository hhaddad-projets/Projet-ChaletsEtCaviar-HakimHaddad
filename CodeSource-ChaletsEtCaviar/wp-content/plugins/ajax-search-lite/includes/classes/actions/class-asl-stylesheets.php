<?php
if (!defined('ABSPATH')) die('-1');

if (!class_exists("WD_ASL_StyleSheets_Action")) {
    /**
     * Class WD_ASL_StyleSheets_Action
     *
     * Handles the non-ajax searches if activated.
     *
     * @class         WD_ASL_StyleSheets_Action
     * @version       1.0
     * @package       AjaxSearchLite/Classes/Actions
     * @category      Class
     * @author        Ernest Marcinko
     */
    class WD_ASL_StyleSheets_Action extends WD_ASL_Action_Abstract {

        /**
         * Holds the inline CSS
         *
         * @var string
         */
        private static $inline_css = "";

        /**
         * This function is bound as the handler
         */
        public function handle() {
            if (function_exists('get_current_screen')) {
                $screen = get_current_screen();
                if (isset($screen) && isset($screen->id) && $screen->id == 'widgets')
                    return;
            }

            add_action('wp_head', array($this, 'inlineCSS'), 10, 0);


            // Don't print if on the back-end
            if ( !is_admin() ) {
                $inst = wd_asl()->instances->get(0);
                $asl_options = $inst['data'];
                wp_register_style('wpdreams-asl-basic', ASL_URL.'css/style.basic.css', array(), ASL_CURR_VER_STRING);
                wp_enqueue_style('wpdreams-asl-basic');
                wp_enqueue_style('wpdreams-ajaxsearchlite', ASL_URL.'css/style-'.$asl_options['theme'].'.css', array(), ASL_CURR_VER_STRING);
            }

            self::$inline_css = "
            @font-face {
                font-family: 'aslsicons2';
                src: url('".str_replace('http:',"",plugins_url())."/ajax-search-lite/css/fonts/icons2.eot');
                src: url('".str_replace('http:',"",plugins_url())."/ajax-search-lite/css/fonts/icons2.eot?#iefix') format('embedded-opentype'),
                     url('".str_replace('http:',"",plugins_url())."/ajax-search-lite/css/fonts/icons2.woff2') format('woff2'),
                     url('".str_replace('http:',"",plugins_url())."/ajax-search-lite/css/fonts/icons2.woff') format('woff'),
                     url('".str_replace('http:',"",plugins_url())."/ajax-search-lite/css/fonts/icons2.ttf') format('truetype'),
                     url('".str_replace('http:',"",plugins_url())."/ajax-search-lite/css/fonts/icons2.svg#icons') format('svg');
                font-weight: normal;
                font-style: normal;
            }
            div[id*='ajaxsearchlitesettings'].searchsettings .asl_option_inner label {
                font-size: 0px !important;
                color: rgba(0, 0, 0, 0);
            }
            div[id*='ajaxsearchlitesettings'].searchsettings .asl_option_inner label:after {
                font-size: 11px !important;
                position: absolute;
                top: 0;
                left: 0;
                z-index: 1;
            }
            div[id*='ajaxsearchlite'].wpdreams_asl_container {
                width: ".$asl_options['box_width'].";
                margin: ".wpdreams_four_to_string($asl_options['box_margin']).";
            }
            div[id*='ajaxsearchliteres'].wpdreams_asl_results div.resdrg span.highlighted {
                font-weight: bold;
                color: ".$asl_options['highlight_color'].";
                background-color: ".$asl_options['highlight_bg_color'].";
            }
            div[id*='ajaxsearchliteres'].wpdreams_asl_results .results div.asl_image {
                width: ". $asl_options['image_width'] . "px;
                height: " . $asl_options['image_height'] . "px;
            }
            div.asl_r .results {
                max-height: ". $asl_options['v_res_max_height'] .";
            }
            ";
            if ( trim($asl_options['box_font']) != '' && $asl_options['box_font'] != 'Open Sans' ) {
                $ffamily = wpd_font('font-family:'.$asl_options['box_font'])." !important;";
                self::$inline_css .= "
                .asl_w, .asl_w * {".$ffamily."}
                .asl_m input[type=search]::placeholder{".$ffamily."}
                .asl_m input[type=search]::-webkit-input-placeholder{".$ffamily."}
                .asl_m input[type=search]::-moz-placeholder{".$ffamily."}
                .asl_m input[type=search]:-ms-input-placeholder{".$ffamily."}
                ";
            }
            if ( $asl_options['override_bg'] == 1 ) {
                self::$inline_css .= "
                .asl_m, .asl_m .probox {
                    background-color: ".$asl_options['override_bg_color']." !important;
                    background-image: none !important;
                    -webkit-background-image: none !important;
                    -ms-background-image: none !important;
                }
                ";
            }
            if ( $asl_options['override_icon'] == 1 ) {
                self::$inline_css .= "
                .asl_m .probox svg {
                    fill: ".$asl_options['override_icon_color']." !important;
                }
                .asl_m .probox .innericon {
                    background-color: ".$asl_options['override_icon_bg_color']." !important;
                    background-image: none !important;
                    -webkit-background-image: none !important;
                    -ms-background-image: none !important;
                }
                ";
            }
            if ( $asl_options['override_border'] == 1 ) {
                self::$inline_css .= "
                div.asl_m.asl_w {
                    ".str_replace(';', ' !important;', $asl_options['override_border_style'])."
                    box-shadow: none !important;
                }
                div.asl_m.asl_w .probox {border: none !important;}
                ";
            }
            if ( $asl_options['custom_css'] != '' && base64_decode($asl_options['custom_css'], true) == true ) {
                self::$inline_css .= ' ' . stripcslashes( base64_decode($asl_options['custom_css']) );
            }
        }

        /**
         * Echos the inline CSS if available
         */
        public function inlineCSS() {
            if (self::$inline_css != "") {
                ?>
                <style type="text/css">
                    <!--
                    <?php echo self::$inline_css; ?>
                    -->
                </style>
                <?php
            }

            /**
             * Compatibility resolution to ajax page loaders:
             *
             * If the _ASL variable is defined at this point, it means that the page was already loaded before,
             * and this header script is executed once again. However that also means that the ASL variable is
             * resetted (due to the localization script) and that the page content is changed, so ajax search pro
             * is not initialized.
             */
            ?>
            <script type="text/javascript">
                if ( typeof _ASL !== "undefined" && _ASL !== null && typeof _ASL.initialize !== "undefined" )
                    _ASL.initialize();
            </script>
            <?php
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