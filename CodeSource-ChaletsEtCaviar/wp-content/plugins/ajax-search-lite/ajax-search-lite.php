<?php
/*
Plugin Name: Ajax Search Lite
Plugin URI: http://wp-dreams.com
Description: The lite version of the most powerful ajax powered search engine for WordPress.
Version: 4.7.22
Author: Ernest Marcinko
Author URI: http://wp-dreams.com
Text Domain: ajax-search-lite
Domain Path: /languages/
*/

/* Prevent direct access */
defined('ABSPATH') or die("You can't access this file directly.");

define('ASL_PATH', plugin_dir_path(__FILE__));
define('ASL_FILE', __FILE__);
define('ASL_INCLUDES_PATH', plugin_dir_path(__FILE__)."/includes/");
define('ASL_CLASSES_PATH', plugin_dir_path(__FILE__)."/includes/classes/");
define('ASL_FUNCTIONS_PATH', plugin_dir_path(__FILE__)."/includes/functions/");
define('ASL_DIR', 'ajax-search-lite');
define(
    'ASL_URL',
    strpos(home_url('/'), 'https://') !== false || strpos(plugin_dir_url(__FILE__), 'https://') !== false ?
    str_replace('http://', 'https://', plugin_dir_url(__FILE__)) : plugin_dir_url(__FILE__)
);
define('ASL_URL_NP',  str_replace(array("http://", "https://"), "//", plugin_dir_url(__FILE__)));
define('ASL_CURRENT_VERSION', 4732);
define('ASL_CURR_VER_STRING', "4.7.22");
define('ASL_DEBUG', 0);
define('ASL_DEMO', get_option('wd_asl_demo', 0) );

// The one and most important global
global $wd_asl;

require_once(ASL_CLASSES_PATH . "core/core.inc.php");
/**
 *  wd_asl()->_prefix   => correct DB prefix for ASP databases
 *  wd_asl()->tables    => table names
 *  wd_asl()->db        => DB manager
 *  wd_asl()->options   => array of default option arrays
 *  wd_asl()->o         => alias of wd_asl()->options
 *  wd_asl()->instances => array of search instances and data
 *  wd_asl()->init      => initialization object
 *  wd_asl()->manager   => main manager object
 */
$wd_asl = new WD_ASL_Globals();

if ( !function_exists("wd_asl") ) {
    /**
     * Easy access of the global variable reference
     *
     * @return WD_ASL_Globals
     */
    function wd_asl() {
        global $wd_asl;
        return $wd_asl;
    }
}

// Initialize the plugin
$wd_asl->manager = WD_ASL_Manager::getInstance();