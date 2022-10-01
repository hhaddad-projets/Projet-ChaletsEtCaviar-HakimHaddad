<?php
if (!defined('ABSPATH')) die('-1');

if (!class_exists("WD_ASL_SearchOverride_Filter")) {
    /**
     * Class WD_ASL_SearchOverride_Filter
     *
     * Handles search override filters
     *
     * @class         WD_ASL_SearchOverride_Filter
     * @version       1.0
     * @package       AjaxSearchPro/Classes/Filters
     * @category      Class
     * @author        Ernest Marcinko
     */
    class WD_ASL_SearchOverride_Filter extends WD_ASL_Filter_Abstract {

        public function handle() {}

        /**
         * Checks and cancels the original search query made by WordPress, if required
         *
         * @param string $query The SQL query
         * @param WP_Query() $wp_query The instance of WP_Query() for this query
         * @return bool
         */
        public function maybeCancelWPQuery($query, $wp_query) {
            if ( $this->checkSearchOverride(true, $wp_query) === true ) {
                $query = false;
            }
            return $query;
        }

        public function override($posts, $wp_query) {

            $checkOverride = $this->checkSearchOverride(false, $wp_query);
            if ( $checkOverride === false) {
                return $posts;
            } else {
                $_p_id = $checkOverride[0];
                $s_data = $checkOverride[1];
            }


            $_POST['options'] = $s_data;
            $_POST['options']['non_ajax_search'] = true;
            $_POST['aslp'] = $_GET['s'];
            $_POST['asl_get_as_array'] = 1;

            // Additional arguments and filters
            add_filter('asl_query_add_args', array($this, 'addAdditionalArgs'), 10, 1);

            $o = WD_ASL_Search_Handler::getInstance();
            $res = $o->handle( true );

            if ( isset($_GET['paged']) ) {
                $paged = $_GET['paged'];
            } else if ( isset($wp_query->query_vars['paged']) ) {
                $paged = $wp_query->query_vars['paged'];
            } else {
                $paged = 1;
            }

            $paged = $paged <= 0 ? 1 : $paged;

            $posts_per_page = (int)get_option('posts_per_page');

            // Get and convert the results needed
            $n_posts = asl_results_to_wp_obj( $res, ( $paged - 1 ) * $posts_per_page, $posts_per_page );

            $wp_query->found_posts = count($res);
            if (($wp_query->found_posts / $posts_per_page) > 1)
                $wp_query->max_num_pages = ceil($wp_query->found_posts / $posts_per_page);
            else
                $wp_query->max_num_pages = 0;

            return $n_posts;
        }

        public function addAdditionalArgs( $args ) {
            global $wpdb;

            // Separate case for WooCommerce
            if ( isset($_GET['post_type']) && $_GET['post_type'] == 'product') {
                // WooCommerce price filter
                if ( isset($_GET['min_price'], $_GET['max_price']) ) {
                    $qry = "( $wpdb->postmeta.meta_value BETWEEN ". ($_GET['min_price'] + 0) ." AND " .($_GET['max_price'] + 0)." )";
                    $args['where'] .= "
                        AND ((
                          SELECT IF(meta_key IS NULL, 1, IF($qry, COUNT(post_id), 0))
                          FROM $wpdb->postmeta
                          WHERE $wpdb->postmeta.post_id = $wpdb->posts.ID AND $wpdb->postmeta.meta_key='_price'
                        ) >= 1)
                    ";
                }

                // WooCommerce custom Ordering
                if ( isset($_GET['orderby']) ) {
                    $o_by = str_replace(' ', '', (strtolower($_GET['orderby'])));
                    switch ( $o_by ) {
                        case 'popularity':
                            $args['fields'] .= "
                            (SELECT IF(meta_value IS NULL, 0, meta_value)
                                FROM $wpdb->postmeta
                                WHERE
                                    $wpdb->postmeta.meta_key='total_sales' AND
                                    $wpdb->postmeta.post_id=$wpdb->posts.ID
                                LIMIT 1
                            ) as customfp,
                            ";
                            $args['orderby'] .= "CAST(customfp as SIGNED) DESC, ";
                            break;
                        case 'rating':
                            // Custom query args here
                            $args['fields'] .= "
                            (
                                SELECT
                                    IF(AVG( $wpdb->commentmeta.meta_value ) IS NULL, 0, AVG( $wpdb->commentmeta.meta_value ))
                                FROM
                                    $wpdb->comments
                                    LEFT JOIN $wpdb->commentmeta ON($wpdb->comments.comment_ID = $wpdb->commentmeta.comment_id)
                                WHERE
                                    $wpdb->posts.ID = $wpdb->comments.comment_post_ID
                                    AND ( $wpdb->commentmeta.meta_key = 'rating' OR $wpdb->commentmeta.meta_key IS null )
                            ) as average_rating,
                            ";
                            $args['orderby'] = "average_rating DESC, ";
                            break;
                        case 'date':
                            $args['orderby'] = 'post_date DESC, ';
                            break;
                        case 'price':
                            $args['fields'] .= "
                            (SELECT IF(meta_value IS NULL, 0, meta_value)
                                FROM $wpdb->postmeta
                                WHERE
                                    $wpdb->postmeta.meta_key='_price' AND
                                    $wpdb->postmeta.post_id=$wpdb->posts.ID
                                LIMIT 1
                            ) as customfp,
                            ";
                            $args['orderby'] .= "CAST(customfp as SIGNED) ASC, ";
                            break;
                        case 'price-desc':
                            $args['fields'] .= "
                            (SELECT IF(meta_value IS NULL, 0, meta_value)
                                FROM $wpdb->postmeta
                                WHERE
                                    $wpdb->postmeta.meta_key='_price' AND
                                    $wpdb->postmeta.post_id=$wpdb->posts.ID
                                LIMIT 1
                            ) as customfp,
                            ";
                            $args['orderby'] .= "CAST(customfp as SIGNED) DESC, ";
                            break;
                    }
                }
            } else if ( isset($_GET['orderby']) ) {
                $o_by = str_replace(' ', '', (strtolower($_GET['orderby'])));
                $o_by_arg = '';
                if ( in_array($o_by, array('id', 'post_id', 'post_title', 'post_date')) ) {
                    $o_by_resolve = array(
                        'id' => 'id', 'post_id' => 'id',
                        'post_title' =>'title',
                        'post_date' => 'date'
                    );
                    $o_by_arg = $o_by_resolve[$o_by];
                    if ( isset($_GET['order']) ) {
                        $o_way = str_replace(' ', '', strtolower($_GET['order']));
                        if ( in_array($o_way, array('asc', 'desc')) )
                            $o_by_arg .= ' ' . $o_way;
                    }
                }

                if ( $o_by_arg != '' ) {
                    $args['orderby'] .= $o_by_arg . ', ';
                }
            }

            return $args;
        }

        /**
         * Checks if the default WordPress search query is executed right now, and if it needs an override.
         * Also sets some cookie and request variables, if needed.
         *
         * @param bool $check_only when true, only checks if the override should be initiated, no variable changes
         * @param WP_Query() $wp_query The instance of WP_Query() for this query
         * @return array|bool
         */
        public function checkSearchOverride($check_only = true, $wp_query) {
            // Is this a search query? Has the override been executed?
            // Is this the admin area?
            // !isset() instead of empty(), because it can be an empty string
            if (
                isset($wp_query) && (
                    !$wp_query->is_main_query() ||
                    !isset($wp_query->query_vars['s']) ||
                    !isset($_GET['s'])
                )
            ) {
                return false;
            }
            // Is this the admin area?
            if ( is_admin() )
                return false;

            // If get method is used, then the cookies are not present
            if (isset($_GET['p_asl_data']) || isset($_GET['np_asl_data'])) {
                if ( $check_only )
                    return true;
                $_p_data = isset($_GET['p_asl_data']) ? $_GET['p_asl_data'] : $_GET['np_asl_data'];
                parse_str(base64_decode($_p_data), $s_data);

                /**
                 * At this point the asl_data cookie should hold the search data, if not, well then this
                 * is just a simple search query.
                 */
            } else if (
                isset($_COOKIE['asl_data'], $_COOKIE['asl_phrase']) &&
                $_COOKIE['asl_phrase'] == $_GET['s']
            ) {
                if ( $check_only )
                    return true;
                parse_str($_COOKIE['asl_data'], $s_data);
                $_POST['np_asl_data'] = $_COOKIE['asl_data'];
            } else {
                // Something is not right
                return false;
            }

            return array(1, $s_data);
        }

        public function fixUrls( $url, $post ) {
            if (isset($post->asl_guid))
                return $post->asl_guid;
            return $url;
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