<?php
/* Prevent direct access */
defined('ABSPATH') or die("You can't access this file directly.");

/**
 * A sample and parent class for keyword suggestion and autocomplete
 */

if (!class_exists('wpd_keywordSuggestAbstract')) {
    /**
     * Statistics database keyword suggestions
     *
     * @class       wpd_keywordSuggest
     * @version     1.0
     * @package     AjaxSearchLite/Classes
     * @category    Class
     * @author      Ernest Marcinko
     */
    abstract class wpd_keywordSuggestAbstract {

        protected  $maxCount;
        protected  $maxCharsPerWord;

        /**
         * This should always return an array of keywords or an empty array
         *
         * @param string $q  search keyword
         * @return array() of keywords
         */
        abstract function getKeywords($q);

        protected function can_get_file() {
            if (function_exists('curl_init')){
                return 1;
            } else if (ini_get('allow_url_fopen')==true) {
                return 2;
            }
            return false;
        }

        protected function url_get_contents($Url, $method) {
            if ($method==2) {
                return file_get_contents($Url);
            } else if ($method==1) {
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $Url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                $output = curl_exec($ch);
                curl_close($ch);
                return $output;
            }
        }
    }
}