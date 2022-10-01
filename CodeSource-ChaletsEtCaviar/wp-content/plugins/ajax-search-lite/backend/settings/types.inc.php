<?php
/**
 * Includes resources for types
 *
 * @author Ernest Marcinko <ernest.marcinko@wp-dreams.com>
 * @version 4.0
 * @link http://wp-dreams.com, http://codecanyon.net/user/anago/portfolio
 * @copyright Copyright (c) 2012, Ernest Marcinko
 */

/* Prevent direct access */
defined('ABSPATH') or die("You can't access this file directly.");

// Include the types
include('class/type.class.php');
include('class/border.class.php');
include('class/categories.class.php');
include('class/colorpicker.class.php');
include('class/colorpickerdummy.class.php');
include('class/customposttypes.class.php');
include('class/customposttypeseditable.class.php');
include('class/customselect.class.php');
include('class/customfields.class.php');
include('class/customfselect.class.php');
include('class/customtaxonomyterm.class.php');
include('class/four.class.php');
include('class/hidden.class.php');
include('class/labelposition.class.php');
include('class/languageselect.class.php');
include('class/numericunit.class.php');
include('class/text.class.php');
include('class/textsmall.class.php');
include('class/textarea.class.php');
include('class/textarea-isparam.class.php');
include('class/upload.class.php');
include('class/yesno.class.php');
include('class/wd_cf_search_callback.class.php');
include('class/wd_textarea_b64.php');

add_action('admin_print_styles', 'admin_stylesV04');
add_action('admin_enqueue_scripts', 'admin_scriptsV04');;

if (!function_exists("admin_scriptsV04")) {
    function admin_scriptsV04() {
        //wp_enqueue_script( $handle, $src, $deps, $ver, $in_footer );
        wp_enqueue_media(); // For image uploader.
        wp_enqueue_script('jquery');
        wp_enqueue_script('thickbox', false, array('jquery'), false, true);
        wp_enqueue_script('jquery-ui-core', false, array('jquery'), false, true);
        wp_enqueue_script('jquery-ui-slider', false, array('jquery-ui-core'), false, true);
        wp_enqueue_script('jquery-ui-tabs', false, array('jquery-ui-core'), false, true);
        wp_enqueue_script('jquery-ui-sortable', false, array('jquery-ui-core'), false, true);
        wp_enqueue_script('jquery-ui-draggable', false, array('jquery-ui-core'), false, true);
        wp_enqueue_script('jquery-ui-datepicker', false, array('jquery-ui-core'), false, true);

        wp_register_script('wpdreams-types', ASL_URL_NP . 'backend/settings/assets/types.js', array(
            'jquery'
        ), ASL_CURR_VER_STRING, true);
        wp_enqueue_script('wpdreams-types');

        wp_register_script('wpdreams-tabs', ASL_URL_NP . 'backend/settings/assets/tabs.js', array(
            'jquery'
        ), ASL_CURR_VER_STRING, true);
        wp_enqueue_script('wpdreams-tabs');

        wp_register_script('wpdreams-upload', ASL_URL_NP . 'backend/settings/assets/upload.js', array(
            'jquery',
            'media-upload',
            'thickbox'
        ), ASL_CURR_VER_STRING, true);
        wp_enqueue_script('wpdreams-upload');

        wp_register_script('wpdreams-misc', ASL_URL_NP . 'backend/settings/assets/misc.js', array(
            'jquery'
        ), ASL_CURR_VER_STRING, true);
        wp_enqueue_script('wpdreams-misc');

        wp_register_script('wpdreams-spectrum', ASL_URL_NP . 'backend/settings/assets/js/spectrum/spectrum.js', array(
            'jquery'
        ), ASL_CURR_VER_STRING, true);
        wp_enqueue_script('wpdreams-spectrum');

        wp_register_script('wpdreams-fonts-jsapi', '//ajax.googleapis.com/ajax/libs/webfont/1.4.7/webfont.js', array('jquery'), ASL_CURR_VER_STRING, true);
        wp_enqueue_script('wpdreams-fonts-jsapi');

        wp_register_script('wpd-modal', ASL_URL_NP . 'backend/settings/assets/wpd-modal/wpd-modal.js', array('jquery'), ASL_CURR_VER_STRING, true);
        wp_enqueue_script('wpd-modal');
    }
}

if (!function_exists("admin_stylesV04")) {
    function admin_stylesV04() {
        wp_register_style('wpdreams-style', ASL_URL_NP . 'backend/settings/assets/style.css', array('wpdreams-tabs'), ASL_CURR_VER_STRING);
        wp_enqueue_style('wpdreams-style');
        wp_register_style('wpdreams-style-hc', ASL_URL_NP . 'backend/settings/assets/style-hc.css', array('wpdreams-tabs'), ASL_CURR_VER_STRING);
        wp_enqueue_style('wpdreams-style-hc');
        wp_enqueue_style('thickbox');
        wp_register_style('wpdreams-jqueryui', 'https://code.jquery.com/ui/1.9.1/themes/base/jquery-ui.css');
        wp_enqueue_style('wpdreams-jqueryui');
        wp_register_style('wpdreams-tabs', ASL_URL_NP . 'backend/settings/assets/tabs.css');
        wp_enqueue_style('wpdreams-tabs');
        wp_register_style('wpdreams-accordion', ASL_URL_NP . 'backend/settings/assets/accordion.css');
        wp_enqueue_style('wpdreams-accordion');
        wp_register_style('wpdreams-spectrum', ASL_URL_NP . 'backend/settings/assets/js/spectrum/spectrum.css');
        wp_enqueue_style('wpdreams-spectrum');
        wp_register_style('wpdreams-animations', ASL_URL_NP . 'backend/settings/assets/animations.css');
        wp_enqueue_style('wpdreams-animations');
        wp_register_style('wpd-modal', ASL_URL_NP . 'backend/settings/assets/wpd-modal/wpd-modal.css');
        wp_enqueue_style('wpd-modal');
    }
}

if (!function_exists("wd_in_array_r")) {
    function wd_in_array_r($needle, $haystack, $strict = true) {
        foreach ($haystack as $item) {
            if (($strict ? $item === $needle : $item == $needle) || (is_array($item) && wd_in_array_r($needle, $item, $strict))) {
                return true;
            }
        }

        return false;
    }
}

if (!function_exists("wpdreams_get_blog_list")) {
    function wpdreams_get_blog_list($start = 0, $num = 10, $deprecated = '') {

        global $wpdb;
        if (!isset($wpdb->blogs))
            return array();
        $blogs = $wpdb->get_results($wpdb->prepare("SELECT blog_id, domain, path FROM $wpdb->blogs WHERE site_id = %d AND public = '1' AND archived = '0' AND mature = '0' AND spam = '0' AND deleted = '0' ORDER BY registered DESC", $wpdb->siteid), ARRAY_A);

        foreach ((array)$blogs as $details) {
            $blog_list[$details['blog_id']] = $details;
            $blog_list[$details['blog_id']]['postcount'] = $wpdb->get_var("SELECT COUNT(ID) FROM " . $wpdb->get_blog_prefix($details['blog_id']) . "posts WHERE post_status='publish' AND post_type='post'");
        }
        unset($blogs);
        $blogs = $blog_list;

        if (false == is_array($blogs))
            return array();

        if ($num == 'all')
            return array_slice($blogs, $start, count($blogs));
        else
            return array_slice($blogs, $start, $num);
    }
}