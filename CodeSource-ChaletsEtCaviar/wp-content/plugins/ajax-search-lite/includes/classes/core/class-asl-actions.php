<?php
if (!defined('ABSPATH')) die('-1');

if ( !class_exists("WD_ASL_Actions") ) {
    /**
     * Class WD_ASL_Actions
     *
     * Registers the plugin Actions, with the proper handler classes.
     * Handling is passed to the handle() method of the specified class.
     * Handlers defined in /classes/actions/class-asp-{handler}.php
     *
     * @class         WD_ASL_Actions
     * @version       1.0
     * @package       AjaxSearchLite/Classes/Core
     * @category      Class
     * @author        Ernest Marcinko
     */
    class WD_ASL_Actions {

        /**
         * Array of internal known actions
         *
         * @var array
         */
        private static $actions = array(
            array(
                "action" => "init",
                "handler" => "Cookies",
                "priority"    => 10,
                "args"  => 0
            ),
            array(
                "action" => "wp_enqueue_scripts",
                "handler" => "StyleSheets",
                "priority"    => 10,
                "args"  => 0
            ),
            array(
                "action" => "wp_head",
                "handler" => "CustomFonts",
                "priority"    => 10,
                "args"  => 0
            ),
            array(
                "action" => "in_admin_header",
                "handler" => "AdminNotices",
                "priority"    => 100000,
                "args"  => 0
            )
        );

        /**
         * Array of already registered handlers
         *
         * @var array
         */
        private static $registered = array();

        /**
         * Registers all the handlers from the $actions variable
         */
        public static function registerAll() {

            foreach (self::$actions as $data) {
                self::register($data['action'], $data['handler'], $data['priority'], $data['args']);

                if ( !empty($data['cron']) )
                    self::registerCron( $data['handler'] );
            }
        }

        /**
         * Get all the queued handlers
         *
         * @return array
         */
        public static function getAll( ) {
            return array_keys(self::$actions);
        }

        /**
         * Get all the already registered handlers
         *
         * @return array
         */
        public static function getRegistered() {
            return self::$registered;
        }

        /**
         * Registers an action with the handler class name.
         *
         * @param $action
         * @param $handler string|array
         * @param int $priority
         * @param int $accepted_args
         * @return bool
         */
        public static function register( $action, $handler, $priority = 10, $accepted_args = 0) {

            if ( is_array($handler) ) {
                $class = "WD_ASL_" . $handler[0] . "_Action";
                $handle = $handler[1];
            } else {
                $class = "WD_ASL_" . $handler . "_Action";
                $handle = "handle";
            }

            if ( !class_exists($class) ) return false;

            add_action($action, array(call_user_func(array($class, 'getInstance')), $handle), $priority, $accepted_args);

            self::$registered[] = $action;

            return true;
        }

        public static function registerCron( $handler ) {
            if ( is_array($handler) ) {
                $class = "WD_ASL_" . $handler[0] . "_Action";
                $handle = "cron_".$handler[1];
            } else {
                $class = "WD_ASL_" . $handler . "_Action";
                $handle = "cron_handle";
            }

            $o = call_user_func(array($class, 'getInstance'));
            $o->$handle();
        }

        /**
         * Deregisters an action handler.
         *
         * @param $action
         * @param $handler
         */
        public static function deregister( $action, $handler ) {

            remove_action($action, array(call_user_func(array($handler, 'getInstance')), 'handle'));

        }

    }
}