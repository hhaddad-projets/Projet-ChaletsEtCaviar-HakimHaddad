<?php
if (!defined('ABSPATH')) die('-1');
/**
 * Class WD_ASL_Globals
 *
 * A container class for the global variables
 *
 * @class         WD_ASL_Globals
 * @version       1.0
 * @package       AjaxSearchLite/Classes/Core
 * @category      Class
 * @author        Ernest Marcinko
 */
class WD_ASL_Globals {

    /**
     * The plugin options and defaults
     *
     * @var array
     */
    public $options;

    /**
     * The plugin options and defaults (shorthand)
     *
     * @var array
     */
    public $o;

    /**
     * Instance of the init class
     *
     * @var WD_ASL_Init()
     */
    public $init;
    /**
     * Instance of the database manager
     *
     * @var WD_ASL_DBMan()
     */
    public $db;

    /**
     * Instance of the instances class
     *
     * @var WD_ASL_Instances()
     */
    public $instances;

    /**
     * Instance of the manager
     *
     * @var WD_ASL_Manager()
     */
    public $manager;

    /**
     * Array of ASP tables
     *
     * @var array
     */
    public $tables;

    /**
     * Holds the correct table prefix for ASP tables
     *
     * @var string
     */
    public $_prefix;

    /**
     * Debug object
     *
     * @var wdDebugData
     */
    public $debug;
}