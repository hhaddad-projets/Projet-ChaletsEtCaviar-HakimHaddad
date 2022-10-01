<?php
/* Prevent direct access */
defined('ABSPATH') or die("You can't access this file directly.");

/**
 * Class WD_ASL_init
 *
 * AJAX SEARCH Lite initializator Class
 */
class WD_ASL_Init {

    /**
     * Core singleton class
     * @var WD_ASL_Init self
     */
    private static $_instance;

    private function __construct() {
        wd_asl()->db = WD_ASL_DBMan::getInstance();

        load_plugin_textdomain( 'ajax-search-lite', false, ASL_DIR . '/languages' );
    }

    /**
     * Runs on activation
     */
    public function activate() {

        WD_ASL_DBMan::getInstance()->create();

        $this->chmod();
        $this->backwards_compatibility_fixes();

        /**
         * Store the version number after everything is done. This is going to help distinguishing
         * stored asl_version from the ASL_CURR_VER variable. These two are different in cases:
         *  - Uninstalling, installing new versions
         *  - Uploading and overwriting old version with a new one
         */
        update_option('asl_version', ASL_CURRENT_VERSION);
    }

    /**
     *  Checks if the user correctly updated the plugin and fixes if not
     */
    public function safety_check() {
        $curr_stored_ver = get_option('asl_version', 0);

        // Run the re-activation actions if this is actually a newer version
        if ($curr_stored_ver != ASL_CURRENT_VERSION) {
            $this->activate();
        }
    }

    /**
     * Fix known backwards incompatibilities
     */
    public function backwards_compatibility_fixes() {
        /*
         * - Get instances
         * - Check options
         * - Transition to new options based on old ones
         * - Save instances
         */

        foreach (wd_asl()->instances->get() as $si) {
            $sd = $si['data'];

            // ------------------------- 4.7.3 -----------------------------
            // Primary and secondary fields
            $values = array('-1', '0', '1', '2', 'c__f');
            $adv_fields = array(
                'titlefield',
                'descriptionfield'
            );
            foreach($adv_fields as $field) {
                // Force string conversion for proper comparision
                if ( !in_array($sd[$field].'', $values) ) {
                    // Custom field value is selected
                    $sd[$field.'_cf'] = $sd[$field];
                    $sd[$field] = 'c__f';
                }
            }

            // ------------------------- 4.7.13 -----------------------------
            if ( isset($sd['redirectonclick']) ) {
                if ( $sd['redirectonclick'] == 0 )
                    $sd['redirect_click_to'] = 'ajax_search';
                unset($sd['redirectonclick']);
            }
            if ( isset($sd['redirect_on_enter']) ) {
                if ( $sd['redirect_on_enter'] == 0 )
                    $sd['redirect_enter_to'] = 'ajax_search';
                unset($sd['redirect_on_enter']);
            }
            // ------------------------- 4.7.14 -----------------------------
            if ( isset($sd['triggeronclick']) ) {
                unset($sd['triggeronclick']);
            }

            // ------------------------- 4.7.15 -----------------------------
            // Before, this was a string
            if ( isset($sd['customtypes']) && !is_array($sd['customtypes']) ) {
                $sd['customtypes'] = explode('|', $sd['customtypes']);
                foreach ($sd['customtypes'] as $ck => $ct) {
                    if ( $ct == '' )
                        unset($sd['customtypes'][$ck]);
                }
            }
            // No longer exists
            if ( isset($sd['selected-customtypes']) )
                unset($sd['selected-customtypes']);
            // No longer exists
            if ( isset($sd['searchinpages']) ) {
                if ( $sd['searchinpages'] == 1 && !in_array('page', $sd['customtypes']) ) {
                    array_unshift($sd['customtypes'] , 'page');
                }
                unset($sd['searchinpages']);
            }
            // No longer exists
            if ( isset($sd['searchinposts']) ) {
                if ( $sd['searchinposts'] == 1 && !in_array('post', $sd['customtypes']) ) {
                    array_unshift($sd['customtypes'] , 'post');
                }
                unset($sd['searchinposts']);
            }

            // At the end, update
            wd_asl()->instances->update(0, $sd);
        }
    }


    /**
     * Extra styles if needed..
     */
    public function styles() {
        if(isset($_SERVER['HTTP_USER_AGENT']) && preg_match('/(?i)msie [6-8]/',$_SERVER['HTTP_USER_AGENT']) ) {
            $comp_options = wd_asl()->o['asl_compatibility'];
            if ( $comp_options['old_browser_compatibility'] == 1 ) {
                return;
            }
        }
    }

    /**
     * Prints the scripts
     */
    public function scripts() {

        $com_opt = wd_asl()->o['asl_compatibility'];
        if(isset($_SERVER['HTTP_USER_AGENT']) && preg_match('/(?i)msie [6-8]/',$_SERVER['HTTP_USER_AGENT']) ) {
            if ( $com_opt['old_browser_compatibility'] == 1 ) {
                return;
            }
        }

        // ------------ Dequeue some scripts causing issues on the back-end --------------
        wp_dequeue_script( 'otw-admin-colorpicker' );
        wp_dequeue_script( 'otw-admin-select2' );
        wp_dequeue_script( 'otw-admin-otwpreview' );
        wp_dequeue_script( 'otw-admin-fonts');
        wp_dequeue_script( 'otw-admin-functions');
        wp_dequeue_script( 'otw-admin-variables');

        $performance_options = wd_asl()->o['asl_performance'];

        $prereq = 'jquery';
        $js_source = $com_opt['js_source'];

        $load_in_footer = w_isset_def($performance_options['load_in_footer'], 1) == 1 ? true : false;
        $load_mcustom = w_isset_def($com_opt['load_mcustom_js'], "yes") == "yes";

        if ($js_source == 'nomin' || $js_source == 'nomin-scoped') {
            if ($js_source == "nomin-scoped") {
                $prereq = "wpdreams-asljquery";
                wp_register_script('wpdreams-asljquery', ASL_URL . 'js/' . $js_source . '/asljquery.js', array(), ASL_CURR_VER_STRING, $load_in_footer);
                wp_enqueue_script('wpdreams-asljquery');
            }
            wp_register_script('wpdreams-gestures', ASL_URL . 'js/' . $js_source . '/jquery.gestures.js', array($prereq), ASL_CURR_VER_STRING, $load_in_footer);
            wp_enqueue_script('wpdreams-gestures');
            wp_register_script('wpdreams-highlight', ASL_URL . 'js/' . $js_source . '/jquery.highlight.js', array($prereq), ASL_CURR_VER_STRING, $load_in_footer);
            wp_enqueue_script('wpdreams-highlight');
            if ($load_mcustom) {
                wp_register_script('wpdreams-scroll', ASL_URL . 'js/' . $js_source . '/jquery.mCustomScrollbar.js', array($prereq), ASL_CURR_VER_STRING, $load_in_footer);
                wp_enqueue_script('wpdreams-scroll');
            }
            wp_register_script('wpdreams-ajaxsearchlite', ASL_URL . 'js/' . $js_source . '/jquery.ajaxsearchlite.js', array($prereq), ASL_CURR_VER_STRING, $load_in_footer);
            wp_enqueue_script('wpdreams-ajaxsearchlite');
            wp_register_script('wpdreams-asl-wrapper', ASL_URL . 'js/' . $js_source . '/asl_wrapper.js', array($prereq, "wpdreams-ajaxsearchlite"), ASL_CURR_VER_STRING, $load_in_footer);
            wp_enqueue_script('wpdreams-asl-wrapper');
        } else {
            wp_enqueue_script('jquery');
            wp_register_script('wpdreams-ajaxsearchlite', ASL_URL . "js/" . $js_source . "/jquery.ajaxsearchlite.min.js", array(), ASL_CURR_VER_STRING, $load_in_footer);
            wp_enqueue_script('wpdreams-ajaxsearchlite');
        }

        $ajax_url = admin_url('admin-ajax.php');
        if ( w_isset_def($performance_options['use_custom_ajax_handler'], 0) == 1 )
            $ajax_url = ASL_URL . 'ajax_search.php';

        if (strpos($com_opt['js_source'], 'min-scoped') !== false) {
            $scope = "asljQuery";
        } else {
            $scope = "jQuery";
        }

        // @deprecated
        wp_localize_script( 'wpdreams-ajaxsearchlite', 'ajaxsearchlite', array(
            'ajaxurl' => $ajax_url,
            'backend_ajaxurl' => admin_url( 'admin-ajax.php'),
            'js_scope' => $scope
        ));

        wp_localize_script( 'wpdreams-ajaxsearchlite', 'ASL', array(
            'ajaxurl' => $ajax_url,
            'backend_ajaxurl' => admin_url( 'admin-ajax.php'),
            'js_scope' => $scope,
            'detect_ajax' => $com_opt['detect_ajax'],
            'scrollbar' => $load_mcustom,
            'js_retain_popstate' => $com_opt['js_retain_popstate'],
            'version' => ASL_CURRENT_VERSION
        ));

    }

    public function pluginReset( $triggerActivate = true ) {
        $options = array(
            'asl_version',
            'asl_glob_d',
            'asl_performance_def',
            'asl_performance',
            'asl_analytics_def',
            'asl_analytics',
            'asl_caching_def',
            'asl_caching',
            'asl_compatibility_def',
            'asl_compatibility',
            'asl_defaults',
            'asl_st_override',
            'asl_woo_override',
            'asl_stat',
            'asl_updates',
            'asl_updates_lc',
            'asl_media_query',
            'asl_performance_stats',
            'asl_recently_updated',
            'asl_debug_data'
        );
        foreach ($options as $o)
            delete_option($o);

        if ( $triggerActivate )
            $this->activate();
    }

    public function pluginWipe() {
        // Options
        $this->pluginReset( false );

        // Database
        wd_asl()->db->delete();

        // Deactivate
        deactivate_plugins(ASL_FILE);
    }


    /**
     *  Tries to chmod the CSS and CACHE directories
     */
    public function chmod() {
        // Nothing to do here yet :)
    }


    /**
     *  If anything we need in the footer
     */
    public function footer() {

    }

    /**
     * Get the instane
     *
     * @return self
     */
    public static function getInstance() {
        if (!(self::$_instance instanceof self)) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }
}