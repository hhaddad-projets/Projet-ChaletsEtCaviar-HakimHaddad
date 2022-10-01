<?php
if (!defined('ABSPATH')) die('-1');

if (!class_exists("WD_ASL_DBMan")) {
    /**
     * Class WD_ASL_DBMan
     *
     * Manager of main database related operations
     *
     * @class         WD_ASL_Manager
     * @version       1.0
     * @package       AjaxSearchLite/Classes/Core
     * @category      Class
     * @author        Ernest Marcinko
     */
    class WD_ASL_DBMan {

        /**
         * All the table slug => name combinations used
         *
         * @since 1.0
         * @var array
         */
        private $tables = array();

        private static $_instance;

        private function __construct() {
            global $wpdb;

            if (isset($wpdb->base_prefix)) {
                wd_asl()->_prefix = $wpdb->base_prefix;
            } else {
                wd_asl()->_prefix = $wpdb->prefix;
            }

            foreach ($this->tables as $slug => $table)
                $this->tables[$slug] = wd_asl()->_prefix . $table;

            // Push the correct table names to the globals back
            $this->tables = (object) $this->tables;
            wd_asl()->tables = $this->tables;
        }

        public function create() {
            // Nothing to do here yet :)
        }

        public function delete() {}


        /**
         * Return the table name by table slug
         *
         * @param $table_slug
         * @return string
         */
        public function table($table_slug) {
            return $this->tables->{$table_slug};
        }

        // ------------------------------------------------------------
        //   ---------------- SINGLETON SPECIFIC --------------------
        // ------------------------------------------------------------

        /**
         * Get the instane of WD_ASL_Manager
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
}