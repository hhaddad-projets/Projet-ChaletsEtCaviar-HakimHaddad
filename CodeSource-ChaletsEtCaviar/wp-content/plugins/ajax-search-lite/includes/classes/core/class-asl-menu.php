<?php
if (!defined('ABSPATH')) die('-1');

if (!class_exists("WD_ASL_Menu")) {
    /**
     * Class WD_ASL_Menu
     *
     * Menu handler for Ajax Search Pro plugin. This class encapsulates the menu elements as well.
     *
     * @class         WD_ASL_Menu
     * @version       1.0
     * @package       AjaxSearchLite/Classes/Core
     * @category      Class
     * @author        Ernest Marcinko
     */
    class WD_ASL_Menu {

        /**
         * Holds the main menu item
         *
         * @var array the main menu
         */
        private static $main_menu = array(
            "title" => "Ajax Search Lite",
            "slug" => "asl_settings",
            "file" => "/backend/settings.php",
            "position" => "206.5",
            "icon_url" => 'icon.png'
        );

        private static $hooks = array();

        /**
         * Submenu titles and slugs
         *
         * @var array
         */
        private static $submenu_items = array(
            array(
                "title" => "Analytics Integration",
                "file" => "/backend/analytics.php",
                "slug" => "asl_analytics"
            ),
            array(
                "title" => "Compatibility Settings",
                "file" => "/backend/compatibility.php",
                "slug" => "asl_compatibility"
            ),
            array(
                "title" => "Performance options",
                "file" => "/backend/performance_options.php",
                "slug" => "asl_performance_options"
            ),
            array(
                "title" => "Maintenance",
                "file" => "/backend/maintenance.php",
                "slug" => "asl_maintenance"
            ),
            array(
                "title" => "Help & Support",
                "file" => "/backend/help_and_support.php",
                "slug" => "asl_help_and_support"
            )
        );

        /**
         * Runs the menu registration process
         */
        public static function register() {

            $capability = ASL_DEMO == 1 ? 'read' : 'manage_options';

            $h = add_menu_page(
                self::$main_menu['title'],
                self::$main_menu['title'],
                $capability,
                self::$main_menu['slug'],
                array("WD_ASL_Menu", "route"),
                ASL_URL . self::$main_menu['icon_url'],
                self::$main_menu['position']
            );
            self::$hooks[$h] = self::$main_menu['slug'];

            foreach (self::$submenu_items as $submenu) {
                $h = add_submenu_page(
                    self::$main_menu['slug'],
                    self::$main_menu['title'],
                    $submenu['title'],
                    $capability,
                    $submenu['slug'],
                    array("WD_ASL_Menu", "route")
                );
                self::$hooks[$h] = $submenu['slug'];
            }

        }

        public static function route() {
            $current_view = self::$hooks[current_filter()];
            include(ASL_PATH.'backend/'.str_replace("asl_", "", $current_view).'.php');
        }

        /**
         * Method to obtain the menu pages for context checking
         *
         * @return array
         */
        public static function getMenuPages() {
            $ret = array();

            $ret[] = self::$main_menu['slug'];

            foreach (self::$submenu_items as $menu)
                $ret[] = $menu['slug'];

            return $ret;
        }

    }
}