<?php
if (!defined('ABSPATH')) die('-1');

if (!class_exists("ASL_Helpers")) {
    /**
     * Class ASL_Helpers
     *
     * Compatibility and other helper functions for data translations
     *
     * @class         ASL_Helpers
     * @version       1.0
     * @package       AjaxSearchPro/Classes/Etc
     * @category      Class
     * @author        Ernest Marcinko
     */
    class ASL_Helpers {

        /**
         * Performs a safe sanitation and escape for strings and numeric values in LIKE type queries.
         * This is not to be used on whole queries, only values.
         *
         * @uses wd_mysql_escape_mimic()
         * @param $string
         * @return array|mixed
         */
        public static function escape( $string ) {

            // recursively go through if it is an array
            if ( is_array($string) ) {
                foreach ($string as $k => $v) {
                    $string[$k] = self::escape($v);
                }
                return $string;
            }

            if ( is_float( $string ) )
                return $string + 0;

            if ( function_exists( 'esc_sql' ) )
                return esc_sql( $string );

            // Okay, what? Not one function is present, use the one we have
            return wd_mysql_escape_mimic($string);
        }

        /**
         * Checks if the given date matches the pattern
         *
         * @param $date
         * @return bool
         */
        public static function check_date( $date ) {
            if ( ASL_mb::strlen( $date ) != 10 ) return false;

            return preg_match('/^[0-9]{4}\-[0-9]{2}\-[0-9]{2}\z/', $date);
        }

        /**
         * Converts a string to number, array of strings to array of numbers
         *
         * Since esc_like() does not escape numeric values, casting them is the easiest way to go
         *
         * @param $number string or array of strings
         * @return mixed number or array of numbers
         */
        public static function force_numeric ( $number ) {
            if ( is_array($number) ) {
                foreach ($number as $k => $v) {
                    $number[$k] = self::force_numeric($v);
                }
                return $number;
            } else {
                // Replace any non-numeric and decimal point character
                $number = preg_replace("/[^0-9\.]+/", "", $number);
                $number = $number + 0;
            }

            return $number;
        }

        /**
         * Generates a string reverse, support multibite strings, plus fallback if mbstring not avail
         *
         * @param $string
         * @return string
         */
        public static function reverseString ( $string ) {

            /*
             * Not sure if using extension_loaded(...) is enough.
             */
            if (
                function_exists('mb_detect_encoding') &&
                function_exists('mb_strlen') &&
                function_exists('mb_substr')
            ) {
                // Using mbstring
                $encoding = mb_detect_encoding($string);
                $length   = mb_strlen($string, $encoding);
                $reversed = '';
                while ($length-- > 0) {
                    $reversed .= mb_substr($string, $length, 1, $encoding);
                }

                return $reversed;

            } else {
                // Good old regex method, still supporting fully UFT8
                preg_match_all('/./us', $string, $ar);
                return implode(array_reverse($ar[0]));
            }

        }

        /**
         * Clears and trims a search phrase from extra slashes and extra space characters
         *
         * @param $s
         * @return mixed
         */
        public static function clear_phrase($s) {
            return preg_replace( '/\s+/', ' ', trim(stripcslashes($s)) );
        }


        /**
         * Removes given tags and it's contents from a text
         *
         * @param string|array $text
         * @param array $tags
         * @return string
         */
        public static function stripTagsWithContent($text, $tags = array()) {
            if ( !is_array($tags) ) {
                $tags = str_replace(',', ' ', $tags);
                $tags = preg_replace('/\s+/', ' ',$tags);
                $tags = explode(' ', $tags);
            }
            foreach ($tags as $tag) {
                $text = preg_replace("/<\\/?" . $tag . "(.|\\s)*?>/", '', $text);
            }
            return $text;
        }

        /**
         * Calculates the weeks passed between two dates
         *
         * @param $date1
         * @param $date2
         * @return int
         */
        public static function datediffInWeeks($date1, $date2) {
            if ( !class_exists('DateTime') )
                return 0;
            if( $date1 > $date2 )
                return datediffInWeeks($date2, $date1);

            $first = DateTime::createFromFormat('m/d/Y', $date1);
            $second = DateTime::createFromFormat('m/d/Y', $date2);

            return floor($first->diff($second)->days/7);
        }

        /**
         * Replaces the first occurrence of the $find string with $replace within the $subject.
         *
         * @since 4.11
         *
         * @param string $find
         * @param string $replace
         * @param string $subject
         * @return string
         */
        public static function replaceFirst($find, $replace, $subject) {
            // From the comments at PHP.net/str_replace
            // Splits $subject into an array of 2 items by $find,
            // and then joins the array with $replace
            return implode($replace, explode($find, $subject, 2));
        }

        public static function resolveBracketSyntax( $content, $fields = array(), $empty_on_missing = false ) {

            if ( empty($fields) )
                return $content;

            // Find conditional patterns, like [prefix {field} suffix]
            preg_match_all( "/(\[.*?\])/", $content, $matches );
            if ( isset( $matches[0] ) && isset( $matches[1] ) && is_array( $matches[1] ) ) {
                foreach ( $matches[1] as $fieldset ) {
                    // Pass on each section to this function again, the code will never get here
                    $stripped_fieldset = str_replace(array('[', ']'), '', $fieldset);
                    $processed_content = ASL_Helpers::resolveBracketSyntax($stripped_fieldset, $fields, true);

                    // Replace the original with the processed version, first occurrence, in case of duplicates
                    $content = ASL_Helpers::replaceFirst($fieldset, $processed_content, $content);
                }
            }

            preg_match_all( "/{(.*?)}/", $content, $matches );
            if ( isset( $matches[0] ) && isset( $matches[1] ) && is_array( $matches[1] ) ) {
                foreach ( $matches[1] as $field ) {
                    $val = isset($fields[$field]) ? $fields[$field] : '';
                    // For the recursive call to break, if any of the fields is empty
                    if ( $empty_on_missing && $val == '')
                        return '';
                    $content = str_replace( '{' . $field . '}', $val, $content );
                }
            }

            return $content;
        }

        /**
         * Gets the custom user meta field value, supporting ACF get_field()
         *
         * @see get_field()                                     ACF post meta parsing.
         * @since 4.12
         *
         * @param string    $field      Custom field label
         * @param object    $r          Result object
         * @param bool      $use_acf    If true, will use the get_field() function from ACF
         * @return string
         */
        public static function getUserCFValue($field, $r, $use_acf = false) {
            $ret = '';

            if ( $use_acf && function_exists('get_field') ) {
                $mykey_values = get_field($field, 'user_'.$r->id, true);
                if (!is_null($mykey_values) && $mykey_values != '' && $mykey_values !== false ) {
                    if (is_array($mykey_values)) {
                        if (!is_object($mykey_values[0])) {
                            $ret = implode(', ', $mykey_values);
                        }
                    } else {
                        $ret = $mykey_values;
                    }
                }
            } else {
                $mykey_values = get_user_meta($r->id, $field);
                if (isset($mykey_values[0])) {
                    $ret = $mykey_values[0];
                }
            }

            return $ret;
        }

        /**
         * Gets the custom field value, supporting ACF get_field() and WooCommerce multi currency
         *
         * @see ASL_Helpers::woo_formattedPriceWithCurrency()   To get the currency formatted field.
         * @see get_field()                                     ACF post meta parsing.
         * @since 4.11
         *
         * @param string    $field      Custom field label
         * @param object    $r          Result object
         * @param bool      $use_acf    If true, will use the get_field() function from ACF
         * @param array     $args       Search arguments
         * @return string
         */
        public static function getCFValue($field, $r, $use_acf = false, $args = array()) {
            $ret = '';
            $price_fields = array('_price', '_tax_price', '_sale_price', '_regular_price');
            $datetime_fields = array('_EventStartDate', '_EventStartDateUTC', '_EventEndDate', '_EventEndDateUTC',
                '_event_start_date', '_event_end_date', '_event_start', '_event_end', '_event_start_local', '_event_end_local');

            if( in_array($field, $datetime_fields) &&
                isset($r->post_type) &&
                in_array($r->post_type, array('event', 'tribe_event')) ) {

                $mykey_values = get_post_custom_values($field, $r->id);
                if (isset($mykey_values[0])) {
                    $ret = date_i18n( get_option( 'date_format' ), strtotime( $mykey_values[0] ) );
                }

            } else if ( in_array($field, $price_fields) &&
                isset($r->post_type) &&
                in_array($r->post_type, array('product', 'product_variation')) &&
                function_exists('wc_get_product')
            ) { // Is this a WooCommerce price related field?
                $ret = ASL_Helpers::woo_formattedPriceWithCurrency($r->id, $field, $args);
            } else { // ..or just a regular field?
                if ( $use_acf && function_exists('get_field') ) {
                    $mykey_values = get_field($field, $r->id, true);
                    if (!is_null($mykey_values) && $mykey_values != '' && $mykey_values !== false ) {
                        if (is_array($mykey_values)) {
                            if (!is_object($mykey_values[0])) {
                                $ret = implode(', ', $mykey_values);
                            }
                        } else {
                            $ret = $mykey_values;
                        }
                    }
                } else {
                    $mykey_values = get_post_custom_values($field, $r->id);
                    if (isset($mykey_values[0])) {
                        $ret = $mykey_values[0];
                    }
                }
            }

            return $ret;
        }

        /**
         * Gets the WooCommerce formatted currency, supporting multiple currencies WPML, WCML
         *
         * @since 4.11
         * @see wc_get_product()    Getting the WooCommerce product.
         * @see $woocommerce_wpml->multi_currency->prices->get_product_price_in_currency() For multi currency parsing.
         * @see wc_price()          Price formatting.
         *
         * @param int       $id         Product or variation ID
         * @param string    $field      Field label
         * @param array     $args       Search arguments
         * @return string
         */
        public static function woo_formattedPriceWithCurrency($id, $field, $args) {
            global $woocommerce_wpml;
            global $sitepress;

            $currency = isset($args['woo_currency']) ?
                $args['woo_currency'] :
                (function_exists('get_woocommerce_currency') ?
                    get_woocommerce_currency() : '');

            $price = '';
            $p = wc_get_product( $id );

            // WCML Section, copied and modified from
            // ..\wp-content\plugins\wpml-woocommerce\inc\currencies\class-wcml-multi-currency-prices.php
            // line 139, function product_price_filter(..)
            if ( isset($sitepress, $woocommerce_wpml, $woocommerce_wpml->multi_currency) ) {
                $original_object_id = apply_filters( 'translate_object_id', $id, get_post_type($id), false, $sitepress->get_default_language() );
                $ccr = get_post_meta($original_object_id, '_custom_conversion_rate', true);

                if( in_array($field, array('_price', '_regular_price', '_sale_price')) && !empty($ccr) && isset($ccr[$field][$currency]) ){
                    $price_original = get_post_meta($original_object_id, $field, true);
                    $price = $price_original * $ccr[$field][$currency];
                } else {
                    $manual_prices = $woocommerce_wpml->multi_currency->custom_prices->get_product_custom_prices($id, $currency);
                    if($manual_prices && !empty($manual_prices[$field])){
                        $price = $manual_prices[$field];
                    } else {
                        // 2. automatic conversion
                        $price = get_post_meta($id, $field, true);
                        $price = apply_filters('wcml_raw_price_amount', $price, $currency );
                    }
                }

                if ( $price != '') {
                    $price = wc_price($price, array('currency' => $currency));
                }
            } else {
                // For variable products _regular_price, _sale_price are not defined
                // ..however are most likely used together. So in case of _regular_price display the range,
                // ..but do not deal with _sale_price at all
                if ( $p->is_type('variable') && !in_array($field, array('_sale_price')) ) {
                    $price = $p->get_price_html();
                } else {
                    switch ($field) {
                        case '_regular_price':
                            $price = $p->get_regular_price();
                            break;
                        case '_sale_price':
                            $price = $p->get_sale_price();
                            break;
                        case '_tax_price':
                            $price = $p->get_price_including_tax();
                            break;
                        default:
                            $price = $p->get_price();
                            break;
                    }
                    if ( $price != '' ) {
                        if ($currency != '')
                            $price = wc_price($price, array('currency' => $currency));
                        else
                            $price = wc_price($price);
                    }
                }
            }

            return $price;
        }

        /**
         * Helper method to be used before printing the font styles. Converts font families to apostrophed versions.
         *
         * @param $font
         * @return mixed
         */
        public static function font($font) {
            preg_match("/family:(.*?);/", $font, $fonts);
            if ( isset($fonts[1]) ) {
                $f = explode(',', str_replace(array('"', "'"), '', $fonts[1]));
                foreach ($f as &$_f) {
                    if ( trim($_f) != 'inherit' )
                        $_f = '"' . trim($_f) . '"';
                    else
                        $_f = trim($_f);
                }
                $f = implode(',', $f);
                return preg_replace("/family:(.*?);/", 'family:'.$f.';', $font);
            } else {
                return $font;
            }
        }
    }
}