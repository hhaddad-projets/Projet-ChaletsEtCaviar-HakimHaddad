<?php
/* Prevent direct access */
defined( 'ABSPATH' ) or die( "You can't access this file directly." );

if ( ! class_exists( 'wpdreams_searchContent' ) ) {
	class wpdreams_searchContent extends wpdreams_search {
	    private $remaining_limit;
        /**
         * @var array of query parts
         */
        private $parts = array();

		protected function do_search() {
			global $wpdb;
			global $q_config;

			$options        = $this->options;
			$sd     = $this->searchData;

			$types           = array();
			//$post_types      = "";
			$term_query      = "(1)";
			//$post_statuses   = "";
			//$term_join       = "";
			$postmeta_join   = "";

			$kw_logic = strtolower( $sd['keyword_logic'] );

            // Prefixes and suffixes
            $pre_field = $this->pre_field;
            $suf_field = $this->suf_field;
            $pre_like  = $this->pre_like;
            $suf_like  = $this->suf_like;

            $s  = $this->s; // full keyword
            $_s = $this->_s;    // array of keywords

            $wcl = '%'; // Wildcard Left
            $wcr = '%'; // Wildcard right
            if ( $options['set_exactonly'] == 1 ) {
                if ( $sd['exact_match_location'] == 'start' )
                    $wcl = '';
                else if ( $sd['exact_match_location'] == 'end' )
                    $wcr = '';
            }

			if ( isset($options['non_ajax_search']) )
				$this->remaining_limit = 500;
			else
				$this->remaining_limit = $sd['maxresults'];

			$q_config['language'] = $options['qtranslate_lang'];

			/*------------------------- Statuses ----------------------------*/
            $post_statuses_arr = explode(',', str_replace(' ', '', w_isset_def($sd['post_status'], 'publish')) );
            // Do not allow private posts for non-editors
            if ( !current_user_can('read_private_posts') )
                $post_statuses_arr = array_diff($post_statuses_arr, array('private'));
            $post_statuses = "'".implode( "','", $post_statuses_arr )."'";
			$post_statuses = "( $wpdb->posts.post_status IN ($post_statuses) )";
			/*---------------------------------------------------------------*/

			/*----------------------- Gather Types --------------------------*/
			if ($options['set_inposts'] == 1)
				$types[] = "post";
			if ($options['set_inpages'])
				$types[] = "page";
			if (isset($options['customset']) && count($options['customset']) > 0)
				$types = array_merge($types, $options['customset']);
			if (count($types) < 1) {
				return '';
			} else {
                $words = implode("','", $types);
                if ( in_array('product_variation', $types) && class_exists('WooCommerce') ) {
                    $_post_types = $types;
                    $_post_types = array_diff($_post_types, array('product_variation'));
                    if (count($_post_types) > 0)
                        $or_ptypes = "OR $wpdb->posts.post_type IN ('".implode("', '", $_post_types)."')";
                    else
                        $or_ptypes = '';
                    $post_types = "
                    ((
                        (
                            $wpdb->posts.post_type = 'product_variation' AND 
                            EXISTS(SELECT 1 FROM $wpdb->posts par WHERE par.ID = $wpdb->posts.post_parent AND par.post_status IN('publish') ) 
                        )  $or_ptypes
                    ))";
                } else {
                    $post_types = "( $wpdb->posts.post_type IN ('$words') )";
                }
			}
			/*---------------------------------------------------------------*/

            /*------------- Custom Fields with Custom selectors -------------*/
            $cf_select = '';
            if ( $sd['woo_exclude_outofstock'] == 1 ) {
                $cf_select = $this->build_cff_query($wpdb->posts . ".ID", array(
                    'post_meta_filter' => array(
                        array(
                            'key'      => '_stock_status',
                            'value'    => 'instock',
                            'operator' => 'ELIKE'
                        )
                    )
                ));
                $cf_select = $cf_select != '' ? ' AND ' . $cf_select : '';
            }
            /*---------------------------------------------------------------*/


            $words = $options['set_exactonly'] == 1 && $s != '' ? array($s) : $_s;
            /**
             * Ex.: When the minimum word count is 2, and the user enters 'a' then $_s is empty.
             *      But $s is not actually empty, thus the wrong query will be executed.
             */
            if ( count($words) == 0 && $s != '' ) {
                $words = array($s);
                // Allow only beginnings
                if ( $options['set_exactonly'] == 0 )
                    $wcl = '';
            }
            if ( $s != '' )
                $words = !in_array($s, $words) ? array_merge(array($s), $words) : $words;

            $relevance_added = false;
            foreach ( $words as $k => $word ) {
                $parts           = array();
                $relevance_parts = array();
                $is_exact = $options['set_exactonly'] == 1 || ( count($words) > 1 && $k == 0 && ($kw_logic == 'or' || $kw_logic == 'and') );

                /*----------------------- Title query ---------------------------*/
                if ( $options['set_intitle'] ) {
                    if ( $kw_logic == 'or' || $kw_logic == 'and' || $is_exact ) {
                        $parts[] = "( " . $pre_field . $wpdb->posts . ".post_title" . $suf_field . " LIKE $pre_like'$wcl" . $word . "$wcr'$suf_like )";
                    } else {
                        $parts[] = "
                               ( " . $pre_field . $wpdb->posts . ".post_title" . $suf_field . " LIKE $pre_like'% " . $word . " %'$suf_like
                            OR  " . $pre_field . $wpdb->posts . ".post_title" . $suf_field . " LIKE $pre_like'" . $word . " %'$suf_like
                            OR  " . $pre_field . $wpdb->posts . ".post_title" . $suf_field . " LIKE $pre_like'% " . $word . "'$suf_like
                            OR  " . $pre_field . $wpdb->posts . ".post_title" . $suf_field . " = '" . $word . "')";
                    }
                    if ( !$relevance_added ) {
                        $relevance_parts[] = "(case when
                    (" . $pre_field . $wpdb->posts . ".post_title" . $suf_field . " LIKE '$s%')
                     then " . (w_isset_def($sd['etitleweight'], 10) * 2) . " else 0 end)";

                        $relevance_parts[] = "(case when
                    (" . $pre_field . $wpdb->posts . ".post_title" . $suf_field . " LIKE '%$s%')
                     then " . w_isset_def($sd['etitleweight'], 10) . " else 0 end)";

                        // The first word relevance is higher
                        if ( isset($_s[0]) ) {
                            $relevance_parts[] = "(case when
                      (" . $pre_field . $wpdb->posts . ".post_title" . $suf_field . " LIKE '%" . $_s[0] . "%')
                       then " . w_isset_def($sd['etitleweight'], 10) . " else 0 end)";
                        }
                    }
                }
                /*---------------------------------------------------------------*/

                /*---------------------- Content query --------------------------*/
                if ( $options['set_incontent'] ) {
                    if ( $kw_logic == 'or' || $kw_logic == 'and' || $is_exact ) {
                        $parts[] = "( " . $pre_field . $wpdb->posts . ".post_content" . $suf_field . " LIKE $pre_like'$wcl" . $word . "$wcr'$suf_like )";
                        /**
                         * Exact matching multi line + word boundary with REGEXP
                         *
                         * $parts[] = "( " . $pre_field . $wpdb->posts . ".post_content" . $suf_field . " REGEXP '([[:blank:][:punct:]]|^|\r\n)" . $word . "([[:blank:][:punct:]]|$|\r\n)' )";
                         */
                    } else {
                        $parts[] = "
                           (" . $pre_field . $wpdb->posts . ".post_content" . $suf_field . " LIKE $pre_like'% " . $word . " %'$suf_like
                        OR  " . $pre_field . $wpdb->posts . ".post_content" . $suf_field . " LIKE $pre_like'" . $word . " %'$suf_like
                        OR  " . $pre_field . $wpdb->posts . ".post_content" . $suf_field . " LIKE $pre_like'% " . $word . "'$suf_like
                        OR  " . $pre_field . $wpdb->posts . ".post_content" . $suf_field . " = '" . $word . "')";
                    }
                    if ( !$relevance_added ) {
                        if ( isset($_s[0]) ) {
                            $relevance_parts[] = "(case when
                            (" . $pre_field . $wpdb->posts . ".post_content" . $suf_field . " LIKE '%" . $_s[0] . "%')
                             then ".w_isset_def($sd['contentweight'], 10)." else 0 end)";
                        }

                        $relevance_parts[] = "(case when
                    (" . $pre_field . $wpdb->posts . ".post_content" . $suf_field . " LIKE '%$s%')
                     then " . w_isset_def($sd['econtentweight'], 10) . " else 0 end)";
                    }
                }
                /*---------------------------------------------------------------*/

                /*----------------- Permalink, post_name query ------------------*/
                if ( $sd['search_in_permalinks'] ) {
                    $parts[] = "( " . $pre_field . $wpdb->posts . ".post_name" . $suf_field . " LIKE $pre_like'$wcl" . $word . "$wcr'$suf_like )";
                }
                /*---------------------------------------------------------------*/

                /*---------------------- Excerpt query --------------------------*/
                if ($options['set_inexcerpt']) {
                    if ( $kw_logic == 'or' || $kw_logic == 'and' || $is_exact ) {
                        $parts[] = "( " . $pre_field . $wpdb->posts . ".post_excerpt" . $suf_field . " LIKE $pre_like'$wcl" . $word . "$wcr'$suf_like )";
                    } else {
                        $parts[] = "
                           (" . $pre_field . $wpdb->posts . ".post_excerpt" . $suf_field . " LIKE $pre_like'% " . $word . " %'$suf_like
                        OR  " . $pre_field . $wpdb->posts . ".post_excerpt" . $suf_field . " LIKE $pre_like'" . $word . " %'$suf_like
                        OR  " . $pre_field . $wpdb->posts . ".post_excerpt" . $suf_field . " LIKE $pre_like'% " . $word . "'$suf_like
                        OR  " . $pre_field . $wpdb->posts . ".post_excerpt" . $suf_field . " = '" . $word . "')";
                    }
                    if ( !$relevance_added ) {
                        if ( isset($_s[0]) ) {
                            $relevance_parts[] = "(case when
                            (" . $pre_field . $wpdb->posts . ".post_excerpt" . $suf_field . " LIKE '%" . $_s[0] . "%')
                             then ".w_isset_def($sd['excerptweight'], 10)." else 0 end)";
                        }

                        $relevance_parts[] = "(case when
                    (" . $pre_field . $wpdb->posts . ".post_excerpt" . $suf_field . " LIKE '%$s%')
                     then " . w_isset_def($sd['eexcerptweight'], 10) . " else 0 end)";
                    }
                }
                /*---------------------------------------------------------------*/

                /*------------------------ Term query ---------------------------*/
                if ($options['searchinterms']) {
                    if ( $kw_logic == 'or' || $kw_logic == 'and' || $is_exact ) {
                        $parts[] = "( " . $pre_field . $wpdb->terms . ".name" . $suf_field . " LIKE $pre_like'$wcl" . $word . "$wcr'$suf_like )";
                    } else {
                        $parts[] = "
                           (" . $pre_field . $wpdb->terms . ".name" . $suf_field . " LIKE $pre_like'% " . $word . " %'$suf_like
                        OR  " . $pre_field . $wpdb->terms . ".name" . $suf_field . " LIKE $pre_like'" . $word . " %'$suf_like
                        OR  " . $pre_field . $wpdb->terms . ".name" . $suf_field . " LIKE $pre_like'% " . $word . "'$suf_like
                        OR  " . $pre_field . $wpdb->terms . ".name" . $suf_field . " = '" . $word . "')";
                    }

                    if ( !$relevance_added ) {
                        $relevance_parts[] = "(case when
                    (" . $pre_field . $wpdb->terms . ".name" . $suf_field . " = '$s')
                     then " . w_isset_def($sd['etermsweight'], 10) . " else 0 end)";
                    }
                }
                /*---------------------------------------------------------------*/

                /*---------------------- Custom Fields --------------------------*/
                $selected_customfields = isset( $sd['selected-customfields'] ) ? $sd['selected-customfields'] : array();
                if ( $sd['search_all_cf'] == 1 )
                    $selected_customfields = array("all");

                if ( is_array($selected_customfields) && count($selected_customfields) > 0 ) {
                    $postmeta_join   = "LEFT JOIN $wpdb->postmeta ON $wpdb->postmeta.post_id = $wpdb->posts.ID";
                    foreach ( $selected_customfields as $cfield ) {
                        $key_part = $sd['search_all_cf'] == 1 ? "" : "$wpdb->postmeta.meta_key='$cfield' AND ";

                        if ( $kw_logic == 'or' || $kw_logic == 'and' || $is_exact ) {
                            $parts[] = "( $key_part " . $pre_field . $wpdb->postmeta . ".meta_value" . $suf_field . " LIKE $pre_like'$wcl" . $word . "$wcr'$suf_like )";
                        } else {
                                $parts[] = "( $key_part 
                           (" . $pre_field . $wpdb->postmeta . ".meta_value" . $suf_field . " LIKE $pre_like'% " . $word . " %'$suf_like
                        OR  " . $pre_field . $wpdb->postmeta . ".meta_value" . $suf_field . " LIKE $pre_like'" . $word . " %'$suf_like
                        OR  " . $pre_field . $wpdb->postmeta . ".meta_value" . $suf_field . " LIKE $pre_like'% " . $word . "'$suf_like
                        OR  " . $pre_field . $wpdb->postmeta . ".meta_value" . $suf_field . " = '" . $word . "') )";
                        }
                        if ( !$relevance_added ) {
                            if ($cfield == 'author_field_name')
                                $relevance_parts[] = "(case when
                            (EXISTS (SELECT 1 FROM $wpdb->postmeta as cfre WHERE cfre.post_id = $wpdb->posts.ID AND cfre.meta_key = '$cfield' AND 
							(cfre.meta_value" . $suf_field . " LIKE '%" . $s . "%')))
                             then 100 else 0 end)";
                            if ($cfield == 'fulltext_field_name')
                                $relevance_parts[] = "(case when
                            (EXISTS (SELECT 1 FROM $wpdb->postmeta as cfre WHERE cfre.post_id = $wpdb->posts.ID AND cfre.meta_key = '$cfield' AND 
							(cfre.meta_value" . $suf_field . " LIKE '%" . $s . "%')))
                             then 10 else 0 end)";
                        }
                    }
                }
                /*---------------------------------------------------------------*/

                /*---------------------- Post CPT IDs ---------------------------*/
                if ( $sd['search_in_ids'] )
                    $parts[] = "($wpdb->posts.ID LIKE '$word')";
                /*---------------------------------------------------------------*/

                $this->parts[] = array( $parts, $relevance_parts );
                $relevance_added = true;
            }


			// ------------------------ Categories/taxonomies ----------------------
			if ( ! isset( $options['categoryset'] ) || $options['categoryset'] == "" ) {
				$options['categoryset'] = array();
			}
			if ( ! isset( $options['termset'] ) || $options['termset'] == "" ) {
				$options['termset'] = array();
			}

			$term_logic = 'and';

			$exclude_categories                          = array();
			$sd['selected-exsearchincategories'] = w_isset_def( $sd['selected-exsearchincategories'], array() );
			$sd['selected-excludecategories']    = w_isset_def( $sd['selected-excludecategories'], array() );

            // New solution
            if ( count( $sd['selected-exsearchincategories'] ) > 0 ||
                count( $sd['selected-excludecategories'] ) > 0 ||
                count( $options['categoryset'] ) > 0 ||
                $sd['showsearchincategories'] == 1
            ) {

                // If the category settings are invisible, ignore the excluded frontend categories, reset to empty array
                if ( $sd['showsearchincategories'] == 0 ) {
                    $sd['selected-exsearchincategories'] = array();
                }

                $_all_cat    = get_terms( 'category', array( 'fields' => 'ids' ) );
                $_needed_cat = array_diff( $_all_cat, $sd['selected-exsearchincategories'] );
                $_needed_cat = ! is_array( $_needed_cat ) ? array() : $_needed_cat;

                // I am pretty sure this is where the devil is born
                /*
                    AND -> Posts NOT in an array of term ids
                    OR  -> Posts in an array of term ids
                  */

                if ( $sd['showsearchincategories'] == 1 ) // If the settings is visible, count for the options
                {
                    $exclude_categories = array_diff( array_merge( $_needed_cat, $sd['selected-excludecategories'] ), $options['categoryset'] );
                } else // ..if the settings is not visible, then only the excluded categories count
                {
                    $exclude_categories = $sd['selected-excludecategories'];
                }


            }

			$exclude_terms = array();

			/*if (w_isset_def($sd['exclude_term_ids'], "") != "") {
				$exclude_terms = explode( ",", str_replace( array("\r", "\n"), '', $sd['exclude_term_ids'] ) );
			}*/

			$all_terms = array_unique( array_merge( $exclude_categories, $exclude_terms ) );

			/**
			 *  New method
			 *
			 *  This is way more efficient, despite it looks more complicated.
			 *  Multiple sub-select is not an issue, since the query can use PRIMARY keys as indexes
			 */
			if ( count( $all_terms ) > 0 ) {
				$words = implode( ',', $all_terms );

				// Quick explanation for the AND
				// .. MAIN SELECT: selects all object_ids that are not in the array
				// .. SUBSELECT:   excludes all the object_ids that are part of the array
				// This is used because of multiple object_ids (posts in more than 1 category)
				if ( $term_logic == 'and' ) {
                    $empty_term_query = "
                    NOT EXISTS (
                        SELECT *
                        FROM $wpdb->term_relationships as xt
                        INNER JOIN $wpdb->term_taxonomy as tt ON ( xt.term_taxonomy_id = tt.term_taxonomy_id AND tt.taxonomy = 'category')
                        WHERE
                            xt.object_id = $wpdb->posts.ID
                    ) OR ";

                    $term_query = " (
                        $empty_term_query

						$wpdb->posts.ID IN (
							SELECT DISTINCT(tr.object_id)
								FROM $wpdb->term_relationships AS tr
				                LEFT JOIN $wpdb->term_taxonomy as tt ON (tr.term_taxonomy_id = tt.term_taxonomy_id AND tt.taxonomy = 'category')
                                    WHERE
                                        tt.term_id NOT IN ($words)
                                        AND tr.object_id NOT IN (
                                            SELECT DISTINCT(trs.object_id)
                                            FROM $wpdb->term_relationships AS trs
                                            LEFT JOIN $wpdb->term_taxonomy as tts ON (trs.term_taxonomy_id = tts.term_taxonomy_id AND tts.taxonomy = 'category')
                                            WHERE tts.term_id IN ($words)
                                        )
                                    )
								)";
				} else {
					$term_query = "( $wpdb->posts.ID IN ( SELECT DISTINCT(tr.object_id) FROM wp_term_relationships AS tr WHERE tr.term_taxonomy_id IN ($words) ) )";
				}
			}

			/*------------------- WooCommerce Visibility --------------------*/
            $woo_visibility_query = '';
            if ( class_exists('WooCommerce') && $sd['exclude_woo_hidden'] == 1 ) {
                // Check if this is version > 3.0
                if ( asp_woo_version_check('3.0') ) {
                    $_t = get_terms(array(
                        'slug' => array('exclude-from-search'),
                        'hide_empty' => 0,
                        'fields' => 'ids'
                    ));

                    if ( !is_wp_error($_t) && count($_t) > 0) {
                        $woo_visibility_query = "
                        NOT EXISTS (
                            SELECT *
                            FROM $wpdb->term_relationships as xt
                            INNER JOIN $wpdb->term_taxonomy as tt ON ( xt.term_taxonomy_id = tt.term_taxonomy_id AND tt.taxonomy = 'product_visibility')
                            WHERE
                                xt.object_id = $wpdb->posts.ID
                        ) OR ";

                        $woo_visibility_query = " AND (
                        $woo_visibility_query
        
                        $wpdb->posts.ID IN (
                        SELECT DISTINCT(tr.object_id)
                            FROM $wpdb->term_relationships AS tr
                            LEFT JOIN $wpdb->term_taxonomy as tt ON (tr.term_taxonomy_id = tt.term_taxonomy_id AND tt.taxonomy = 'product_visibility')
                                WHERE
                                    tt.term_id NOT IN (".implode(', ', $_t).")
                                    AND tr.object_id NOT IN (
                                        SELECT DISTINCT(trs.object_id)
                                        FROM $wpdb->term_relationships AS trs
                                        LEFT JOIN $wpdb->term_taxonomy as tts ON (trs.term_taxonomy_id = tts.term_taxonomy_id AND tts.taxonomy = 'product_visibility')
                                        WHERE tts.term_id IN (".implode(', ', $_t).")
                                    )
                                )
                            )";
                    }
                } else {
                    // Fallback to < 3.0
                    $qry = "( $wpdb->postmeta.meta_value IN ('visible', 'search') )";
                    $woo_visibility_query .= "
                    AND ((
                      SELECT IF(meta_key IS NULL, 1, IF($qry, COUNT(post_id), 0))
                      FROM $wpdb->postmeta
                      WHERE $wpdb->postmeta.post_id = $wpdb->posts.ID AND $wpdb->postmeta.meta_key='_visibility'
                    ) >= 1)
                    ";
                }

            }
			/*---------------------------------------------------------------*/

			/*------------------------ Exclude id's -------------------------*/
			if ( isset( $sd['excludeposts'] ) && $sd['excludeposts'] != "" ) {
				$exclude_posts = "($wpdb->posts.ID NOT IN (" . $sd['excludeposts'] . "))";
			} else {
				$exclude_posts = "($wpdb->posts.ID NOT IN (-55))";
			}
			/*---------------------------------------------------------------*/

			/*------------------------ Term JOIN -------------------------*/
			// If the search in terms is not active, we don't need this unnecessary big join
			$term_join = "";
			if ( $options['searchinterms'] ) {
				$term_join = "
                LEFT JOIN $wpdb->term_relationships ON $wpdb->posts.ID = $wpdb->term_relationships.object_id
                LEFT JOIN $wpdb->term_taxonomy ON $wpdb->term_taxonomy.term_taxonomy_id = $wpdb->term_relationships.term_taxonomy_id
                LEFT JOIN $wpdb->terms ON $wpdb->term_taxonomy.term_id = $wpdb->terms.term_id";
			}
			/*---------------------------------------------------------------*/

			/*------------------------- Build like --------------------------*/
			/*$like_query = implode( ' OR ', $parts );
			if ( $like_query == "" ) {
				$like_query = "(1)";
			} else {
				$like_query = "($like_query)";
			}*/
			/*---------------------------------------------------------------*/

			/*---------------------- Build relevance ------------------------*/
			/*$relevance = implode( ' + ', $relevance_parts );
			if ( $relevance == "" ) {
				$relevance = "(1)";
			} else {
				$relevance = "($relevance)";
			}*/
			/*---------------------------------------------------------------*/


			/*------------------------- WPML filter -------------------------*/
            $wpml_query = "(1)";
            if ( isset( $options['wpml_lang'] )
                && w_isset_def( $sd['wpml_compatibility'], 1 ) == 1
            ) {
                global $sitepress;
                $site_lang_selected = false;
				$wpml_post_types_arr = array();

				foreach ($types as $tt) {
					$wpml_post_types_arr[] = "post_" . $tt;
				}
				$wpml_post_types = implode( "','", $wpml_post_types_arr );

                // Let us get the default site language if possible
                if ( is_object($sitepress) && method_exists($sitepress, 'get_default_language') ) {
                    $site_lang_selected = $sitepress->get_default_language() == $options['wpml_lang'] ? true : false;
                }

                $_wpml_query_id_field = "$wpdb->posts.ID";
                // Product variations are not translated, so we need to use the parent ID (product) field to compare
                if ( in_array('product_variation', $types) ) {
                    $_wpml_query_id_field = "(IF($wpdb->posts.post_type='product_variation', $wpdb->posts.post_parent, $wpdb->posts.ID))";
                }

                $wpml_query = "
				EXISTS (
					SELECT DISTINCT(wpml.element_id)
					FROM " . $wpdb->base_prefix . "icl_translations as wpml
					WHERE
	                    $_wpml_query_id_field = wpml.element_id AND
	                    wpml.language_code = '" . ASL_Helpers::escape( $options['wpml_lang'] ) . "' AND
	                    wpml.element_type IN ('$wpml_post_types')
                )";

				/**
				 * For missing translations..
				 * If the site language is used, the translation can be non-existent
				 */
                if ($site_lang_selected) {
                    $wpml_query = "
                    NOT EXISTS (
                        SELECT DISTINCT(wpml.element_id)
                        FROM " . $wpdb->base_prefix . "icl_translations as wpml
                        WHERE
                            $_wpml_query_id_field = wpml.element_id AND
                            wpml.element_type IN ('$wpml_post_types')
                    ) OR
                    " . $wpml_query;
                }
            }
			/*---------------------------------------------------------------*/

			/*----------------------- POLYLANG filter -----------------------*/
			$polylang_query = "";
			if (isset( $options['polylang_lang'] ) &&
				$options['polylang_lang'] != "" &&
				$sd['polylang_compatibility'] == 1
			) {
				$languages = get_terms('language', array(
								'hide_empty' => false,
								'fields' => 'ids',
								'orderby' => 'term_group',
								'slug' => $options['polylang_lang'])
				);
				if ( !empty($languages) && !is_wp_error($languages) && isset($languages[0]) ) {
					$polylang_query = " AND (
                    $wpdb->posts.ID IN ( SELECT DISTINCT(tr.object_id)
                        FROM $wpdb->term_relationships AS tr
                        LEFT JOIN $wpdb->term_taxonomy as tt ON (tr.term_taxonomy_id = tt.term_taxonomy_id AND tt.taxonomy = 'language')
                        WHERE tt.term_id = $languages[0]
			         ) )";
				}
			}
			/*---------------------------------------------------------------*/

            /*--------------------- Other Query stuff -----------------------*/
            // If the content is hidden, why select it..
            if ($sd['showdescription'] == 0)
                $select_content = "''";
            else
                $select_content  = $wpdb->posts. ".post_content";

            // Dont select excerpt if its not used at all
            $select_excerpt = (
                w_isset_def($sd['titlefield'], 0) == 1 ||
                w_isset_def($sd['descriptionfield'], 0) == 1
            ) ? $wpdb->posts. ".post_excerpt" : "''";
            /*---------------------------------------------------------------*/

			$querystr = "
    	SELECT
			{args_fields}
			$wpdb->posts.post_title as title,
			$wpdb->posts.ID as id,
			$wpdb->posts.post_date as date,
			$select_content as content,
			$select_excerpt as excerpt,
			'pagepost' as content_type,
			(SELECT
				$wpdb->users.display_name as author
				FROM $wpdb->users
				WHERE $wpdb->users.ID = $wpdb->posts.post_author
			) as author,
			'' as ttid,
			$wpdb->posts.post_type as post_type,
			{relevance_query} as relevance
    	FROM $wpdb->posts
			$postmeta_join
			$term_join
			{args_join}
    	WHERE
                $post_types
                $woo_visibility_query
                $cf_select
            AND $post_statuses
            AND $term_query
            AND {like_query}
            AND $exclude_posts
            AND ( $wpml_query )
            $polylang_query
            {args_where}
        GROUP BY
          	{args_groupby} $wpdb->posts.ID
        ORDER BY
        	{args_orderby} ".$sd['orderby_primary'].", ".$sd['orderby_secondary'].", id DESC
        LIMIT " . $this->remaining_limit;

			$_qargs = array(
				'fields' => '',
				'join' => '',
				'where' => '',
				'orderby' => '',
				'groupby' => ''
			);
			$_qargs = apply_filters('asl_query_add_args', $_qargs, $sd, $options);
			// Place the argument query fields
			if ( is_array($_qargs) ) {
				$querystr = str_replace(
						array('{args_fields}', '{args_join}', '{args_where}', '{args_orderby}', '{args_groupby}'),
						array($_qargs['fields'], $_qargs['join'], $_qargs['where'], $_qargs['orderby'], $_qargs['groupby']),
						$querystr
				);
			} else {
				$querystr = str_replace(
						array('{args_fields}', '{args_join}', '{args_where}', '{args_orderby}', '{args_groupby}'),
						'',
						$querystr
				);
			}

			$querystr = $this->build_query( $this->parts, $querystr, array(
                'keyword_logic' => $kw_logic,
                '_post_use_relevance' => 1
            ));
			$pageposts = $wpdb->get_results( $querystr, OBJECT );

            wd_asl()->debug->pushData(
                array(
                    "phrase"  =>  $s,
                    "options" =>  $options,
                    "query" =>    $querystr,
                    "results" =>  count($pageposts)
                ),
                "queries", false, true, false, 5
            );

			$this->results = $pageposts;
			return $pageposts;
		}

        /**
         * Builds the query from the parts
         *
         * @param $parts array LIKE and Relevance parts
         * @param $query string the search query
         * @param $argp array arguments passed
         *
         * @return string query
         */
        protected function build_query( $parts, $query, $argp = array() ) {

            $args = array_merge(array(
                'keyword_logic' => 'OR',
                '_post_use_relevance' => 1
            ), $argp);
            $kw_logic = str_replace('EX', '', strtoupper( $args['keyword_logic'] ) );
            $kw_logic = $kw_logic != 'AND' && $kw_logic != 'OR' ? 'AND' : $kw_logic;

            $r_parts = array(); // relevance parts

            /*------------------------- Build like --------------------------*/
            $exact_query = '';
            $like_query_arr = array();
            foreach ( $parts as $k=>$part ) {
                if ( $k == 0 )
                    $exact_query = '(' . implode(' OR ', $part[0]) . ')';
                else
                    $like_query_arr[] = '(' . implode(' OR ', $part[0]) . ')';
            }
            $like_query = implode(' ' . $kw_logic . ' ', $like_query_arr);

            // When $exact query is empty, then surely $like_query must be empty too, see above
            if ( $exact_query == '' ) {
                $like_query = "(1)";
            } else {
                // Both $like_query and $exact_query set
                if ( $like_query != '' ) {
                    $like_query = "( $exact_query OR $like_query )";
                } else {
                    $like_query = "( $exact_query )";
                }
            }
            /*---------------------------------------------------------------*/

            /*---------------------- Build relevance ------------------------*/
            foreach ( $parts as $part ) {
                if ( isset($part[1]) && count($part[1]) > 0 )
                    $r_parts = array_merge( $r_parts, $part[1] );
            }
            $relevance = implode( ' + ', $r_parts );
            if ( $args['_post_use_relevance'] != 1 || $relevance == "" ) {
                $relevance = "(1)";
            } else {
                $relevance = "($relevance)";
            }
            /*---------------------------------------------------------------*/

            if ( isset($this->remaining_limit, $this->limit_start) ) {
                if ($this->limit_start != 0)
                    $limit = $this->limit_start . ", " . $this->remaining_limit;
                else
                    $limit = $this->remaining_limit;
            } else {
                $limit = 10;
            }

            return str_replace(
                array( "{relevance_query}", "{like_query}", "{remaining_limit}" ),
                array( $relevance, $like_query, $limit ),
                $query
            );

        }

        protected function build_cff_query( $post_id_field, $args ) {
            global $wpdb;
            $defaults = array(
                '_post_meta_allow_null' => 1,
                '_post_meta_logic' => 'AND',
                'post_meta_filter' => array()
            );
            $args = wp_parse_args( $args, $defaults );
            $parts = array();

            $allow_cf_null = $args['_post_meta_allow_null'];

            foreach ( $args['post_meta_filter'] as $data ) {

                $operator = $data['operator'];
                $posted = $data['value'];
                $field = $data['key'];

                // Is this a special case of date operator?
                if (strpos($operator, "datetime") === 0) {
                    switch ($operator) {
                        case 'datetime =':
                            $current_part = "($wpdb->postmeta.meta_value BETWEEN '$posted 00:00:00' AND '$posted 23:59:59')";
                            break;
                        case 'datetime <>':
                            $current_part = "($wpdb->postmeta.meta_value NOT BETWEEN '$posted 00:00:00' AND '$posted 23:59:59')";
                            break;
                        case 'datetime <':
                            $current_part = "($wpdb->postmeta.meta_value < '$posted 00:00:00')";
                            break;
                        case 'datetime >':
                            $current_part = "($wpdb->postmeta.meta_value > '$posted 23:59:59')";
                            break;
                        default:
                            $current_part = "($wpdb->postmeta.meta_value < '$posted 00:00:00')";
                            break;
                    }
                    // Is this a special case of timestamp?
                } else if (strpos($operator, "timestamp") === 0) {
                    switch ($operator) {
                        case 'timestamp =':
                            $current_part = "($wpdb->postmeta.meta_value BETWEEN $posted AND ".($posted + 86399).")";
                            break;
                        case 'timestamp <>':
                            $current_part = "($wpdb->postmeta.meta_value NOT BETWEEN $posted AND ".($posted + 86399).")";
                            break;
                        case 'timestamp <':
                            $current_part = "($wpdb->postmeta.meta_value < $posted)";
                            break;
                        case 'timestamp >':
                            $current_part = "($wpdb->postmeta.meta_value > ".($posted + 86399).")";
                            break;
                        default:
                            $current_part = "($wpdb->postmeta.meta_value < $posted)";
                            break;
                    }
                    // Check BETWEEN first -> range slider
                } else if ( $operator === "BETWEEN" ) {
                    $current_part = "($wpdb->postmeta.meta_value BETWEEN " . $posted[0] . " AND " . $posted[1] . " )";
                    // If not BETWEEN but value is array, then drop-down or checkboxes
                } else if ( is_array($posted) ) {
                    // Is there a logic sent?
                    $logic  = isset($data['logic']) ? $data['logic'] : "OR";
                    $values = '';
                    if ($operator === "IN" ) {
                        $val = implode("','", $posted);
                        if ( !empty($val) ) {
                            if ($values != '') {
                                $values .= " $logic $wpdb->postmeta.meta_value $operator ('" . $val . "')";
                            } else {
                                $values .= "$wpdb->postmeta.meta_value $operator ('" . $val . "')";
                            }
                        }
                    } else {
                        foreach ($posted as $v) {
                            if ($operator === "ELIKE") {
                                if ($values != '') {
                                    $values .= " $logic $wpdb->postmeta.meta_value $operator '" . $v . "'";
                                } else {
                                    $values .= "$wpdb->postmeta.meta_value $operator '" . $v . "'";
                                }
                            } else if ($operator === "NOT LIKE" || $operator === "LIKE") {
                                if ($values != '') {
                                    $values .= " $logic $wpdb->postmeta.meta_value $operator '%" . $v . "%'";
                                } else {
                                    $values .= "$wpdb->postmeta.meta_value $operator '%" . $v . "%'";
                                }
                            } else {
                                if ($values != '') {
                                    $values .= " $logic $wpdb->postmeta.meta_value $operator " . $v;
                                } else {
                                    $values .= "$wpdb->postmeta.meta_value $operator " . $v;
                                }
                            }
                        }
                    }

                    $values  = $values == '' ? '0' : $values;
                    $current_part = "($values)";
                    // String operations
                } else if ($operator === "NOT LIKE" || $operator === "LIKE") {
                    $current_part = "($wpdb->postmeta.meta_value $operator '%" . $posted . "%')";
                } else if ($operator === "ELIKE") {
                    $current_part = "($wpdb->postmeta.meta_value LIKE '$posted')";
                    // Numeric operations or problematic stuff left
                } else {
                    $current_part = "($wpdb->postmeta.meta_value $operator $posted  )";
                }

                // Finally add the current part to the parts array
                if ( $current_part != "") {
                    if ( isset($data['allow_missing']) )
                        $allowance = $data['allow_missing'];
                    else
                        $allowance = $allow_cf_null;

                    $parts[] = array($field, $current_part, $allowance);
                }
            }

            // The correct count is the unique fields count
            //$meta_count = count( $unique_fields );

            $cf_select = "";
            $cf_select_arr = array();

            /**
             * NOTE 1:
             * With the previous NOT EXISTS(...) subquery solution the search would hang in some cases
             * when checking if empty values are allowed. No idea why though...
             * Eventually using separate sub-queries for each field is the best.
             *
             * NOTE 2:
             * COUNT(post_id) is a MUST in the nested IF() statement !! Otherwise the query will return empty rows, no idea why either..
             */

            foreach ( $parts as $k => $part ) {
                $def = $part[2] ? "(
                    SELECT IF((meta_key IS NULL OR meta_value = ''), -1, COUNT(meta_id))
                    FROM $wpdb->postmeta
                    WHERE $wpdb->postmeta.post_id = $post_id_field AND $wpdb->postmeta.meta_key='$field'
                    LIMIT 1
                  ) = -1
                 OR" : '';                  // Allowance
                $field = $part[0];          // Field name
                $qry = $part[1];            // Query condition
                $cf_select_arr[] = "
                (
                  $def
                  (
                    SELECT COUNT(meta_id) as mtc
                    FROM $wpdb->postmeta
                    WHERE $wpdb->postmeta.post_id = $post_id_field AND $wpdb->postmeta.meta_key='$field' AND $qry
                    GROUP BY meta_id
                    ORDER BY mtc
                    LIMIT 1
                  ) >= 1
                )";
            }
            if ( count($cf_select_arr) ) {
                // Connect them based on the meta logic
                $cf_select = "( ". implode( $args['_post_meta_logic'], $cf_select_arr ) . " )";
            }

            return $cf_select;
        }

		protected function post_process() {

			$pageposts  = is_array( $this->results ) ? $this->results : array();
			$options    = $this->options;
			$sd = $this->searchData;
			$s          = $this->s;
			$_s         = $this->_s;

			// No post processing is needed on non-ajax search
			if ( isset($options['non_ajax_search']) ) {
				$this->results = $pageposts;
				return $pageposts;
			}

            $performance_options = get_option('asl_performance');
            $comp_options = wd_asl()->o['asl_compatibility'];

			foreach ( $pageposts as $k => $v ) {
				$r          = &$pageposts[ $k ];
				$r->title   = w_isset_def( $r->title, null );
				$r->content = w_isset_def( $r->content, null );
				$r->image   = w_isset_def( $r->image, null );
				$r->author  = w_isset_def( $r->author, null );
				$r->date    = w_isset_def( $r->date, null );
			}

			/* Images, title, desc */
			foreach ( $pageposts as $k => $v ) {

				// Let's simplify things
				$r = &$pageposts[ $k ];

				$r->title   = apply_filters( 'asl_result_title_before_prostproc', $r->title, $r->id );
				$r->content = apply_filters( 'asl_result_content_before_prostproc', $r->content, $r->id );
				$r->image   = apply_filters( 'asl_result_image_before_prostproc', $r->image, $r->id );
				$r->author  = apply_filters( 'asl_result_author_before_prostproc', $r->author, $r->id );
				$r->date    = apply_filters( 'asl_result_date_before_prostproc', $r->date, $r->id );

				// -------------------------- Woocommerce Fixes -----------------------------
				// ---- URL FIX for WooCommerce product variations
                $wc_prod_var_o = null; // Reset for each loop
				if ( $r->post_type == 'product_variation' && function_exists('wc_get_product') ) {
                    $wc_prod_var_o = wc_get_product( $r->id );
                    $r->link       = $wc_prod_var_o->get_permalink();
				} else {
					$r->link = get_permalink( $v->id );
				}
				// --------------------------------------------------------------------------
                // Filter it though WPML
                if ( isset( $options['wpml_lang'] )
                    && w_isset_def( $sd['wpml_compatibility'], 1 ) == 1
                )
                    $r->link = apply_filters( 'wpml_permalink', $r->link, ASL_Helpers::escape( $options['wpml_lang'] ) );

				$image_settings = $sd['image_options'];
				if ( $image_settings['show_images'] != 0 ) {

					$im = $this->getBFIimage( $r );

					if ( $im != '' && strpos( $im, "mshots/v1" ) === false && w_isset_def($performance_options['image_cropping'], 0) == 1 ) {
						if ( w_isset_def( $image_settings['image_transparency'], 1 ) == 1 ) {
							$bfi_params = array( 'width'  => $image_settings['image_width'],
							                     'height' => $image_settings['image_height'],
							                     'crop'   => true
							);
						} else {
							$bfi_params = array( 'width'  => $image_settings['image_width'],
							                     'height' => $image_settings['image_height'],
							                     'crop'   => true,
							                     'color'  => wpdreams_rgb2hex( $image_settings['image_bg_color'] )
							);
						}

						$r->image = bfi_thumb( $im, $bfi_params );
					} else {
						$r->image = $im;
					}
				}

				switch($sd['titlefield']) {
					case '0':
						if ( isset($wc_prod_var_o) ) {
							$r->title = $wc_prod_var_o->get_title();
						} else {
							$r->title = get_the_title($r->id);
						}
						break;
					case '1':
						if ( ASL_mb::strlen( $r->excerpt ) >= 200 ) {
							$r->title = wd_substr_at_word( $r->excerpt, 200 );
						} else {
							$r->title = $r->excerpt;
						}
						break;
					case 'c__f':
						if ( $sd['titlefield_cf'] != '' ) {
						    if ( $comp_options['use_acf_getfield'] == 1 && function_exists('get_field') ) {
                                $mykey_values = get_field($sd['titlefield_cf'], $r->id, true);
                                if (!is_null($mykey_values) && $mykey_values != '' && $mykey_values !== false ) {
                                    if (is_array($mykey_values)) {
                                        if (!is_object($mykey_values[0])) {
                                            $r->title = implode(', ', $mykey_values);
                                            break;
                                        }
                                    } else {
                                        $r->title = $mykey_values;
                                        break;
                                    }
                                }
                            } else {
                                $mykey_values = get_post_custom_values($sd['titlefield_cf'], $r->id);
                                if (isset($mykey_values[0])) {
                                    $r->title = $mykey_values[0];
                                    break;
                                }
                            }
						}
						break;
					default:
						if ( isset($wc_prod_var_o) ) {
							$r->title = $wc_prod_var_o->get_title();
						} else {
							$r->title = get_the_title($r->id);
						}
						break;
				}

				if ( ! isset( $sd['striptagsexclude'] ) ) {
					$sd['striptagsexclude'] = "<a><span>";
				}

				$_content = '';
				switch ($sd['descriptionfield']) {
                    case '1':
                        if (function_exists('qtranxf_use')) {
                            global $q_config;
                            $r->excerpt = qtranxf_use($q_config['default_language'], $r->excerpt, false);
                        }
                        $_content = $r->excerpt;
                        break;
                    case '2':
                        $_content = strip_tags(get_the_title($r->id), $sd['striptagsexclude']);
                        break;
                    case 'c__f':
                        if ($sd['descriptionfield_cf'] != '') {
                            if ($comp_options['use_acf_getfield'] == 1 && function_exists('get_field')) {
                                $mykey_values = get_field($sd['descriptionfield_cf'], $r->id, true);
                                if (!is_null($mykey_values) && $mykey_values != '' && $mykey_values !== false ) {
                                    if ( is_array($mykey_values) ) {
                                        if ( !is_object($mykey_values[0]) ) {
                                            $_content = implode(', ', $mykey_values);
                                            break;
                                        }
                                    } else {
                                        $_content = $mykey_values;
                                        break;
                                    }
                                }
                            } else {
                                $mykey_values = get_post_custom_values($sd['descriptionfield_cf'], $r->id);
                                if (isset($mykey_values[0])) {
                                    $_content = $mykey_values[0];
                                    break;
                                }
                            }
                        }
                        break;
					default: //including option '0', alias content
						if ( function_exists( 'qtranxf_use' ) ) {
							global $q_config;
							$r->content = qtranxf_use($q_config['default_language'], $r->content, false);
						}
						// For product variations, do something special
						if ( isset($wc_prod_var_o) ) {
                            $r->content = $wc_prod_var_o->get_description();
                            if ( $r->content == '') {
                                $_pprod = wc_get_product($wc_prod_var_o->get_parent_id());
                                $r->content = $_pprod->get_description();
                            }
						}
						$_content = $r->content;
						break;
				}

				if ( $_content == "" && $r->content != '') {
					$_content = $r->content;
				}

				// Deal with the shortcodes here, for more accuracy
				if ( $sd['shortcode_op'] == "remove" ) {
					if ( $_content != "" ) {
						// Remove shortcodes, keep the content, really fast and effective method
						$_content = preg_replace("~(?:\[/?)[^\]]+/?\]~su", '', $_content);
					}
				} else {
					if ( $_content != "" ) {
						$_content = apply_filters( 'the_content', $_content );
					}
				}

				// Remove styles and scripts
				$_content = preg_replace( array(
					'#<script(.*?)>(.*?)</script>#is',
					'#<style(.*?)>(.*?)</style>#is'
				), '', $_content );

				$_content = strip_tags( $_content );

                // Get the words from around the search phrase, or just the description
                if ( w_isset_def($sd['description_context'], 1) == 1 && count( $_s ) > 0 && $s != '' ) {
					// Try for an exact match
					$_ex_content = $this->context_find(
							$_content, $s,
							floor($sd['descriptionlength'] / 6),
							$sd['descriptionlength'],
							50000,
							true
					);
					if ( $_ex_content === false ) {
						// No exact match, go with the first keyword
						$_content = $this->context_find(
								$_content, $_s[0],
								floor($sd['descriptionlength'] / 6),
								$sd['descriptionlength'],
								50000
						);
					} else {
						$_content = $_ex_content;
					}
				} else if ( $_content != '' && ( strlen( $_content ) > $sd['descriptionlength'] ) ) {
					$_content = wd_substr_at_word($_content, $sd['descriptionlength']) . "...";
				}

				$_content   = wd_closetags( $_content );
				$r->content = $_content;

				$r->title   = apply_filters( 'asl_result_title_after_prostproc', $r->title, $r->id );
				$r->content = apply_filters( 'asl_result_content_after_prostproc', $r->content, $r->id );
				$r->image   = apply_filters( 'asl_result_image_after_prostproc', $r->image, $r->id );
				$r->author  = apply_filters( 'asl_result_author_after_prostproc', $r->author, $r->id );
				$r->date    = apply_filters( 'asl_result_date_after_prostproc', $r->date, $r->id );

			}
			/* !Images, title, desc */
			$this->results = $pageposts;

			return $pageposts;

		}

		protected function group() {
			return $this->results;
		}

		/**
		 * Fetches an image for BFI class
         * @param $post WP_POst Post object
         * @return string image URL
		 */
		function getBFIimage( $post ) {
            $sd = $this->searchData;

			if ( ! isset( $post->image ) || $post->image == null ) {

				if ( ! isset( $post->id ) ) {
					return "";
				}
				$im = "";
				for ( $i = 1; $i < 6; $i ++ ) {
					switch ( $this->imageSettings[ 'image_source' . $i ] ) {
						case "featured":
							if ( $this->imageSettings['image_source_featured'] == "original" ) {
								$im = wp_get_attachment_url(get_post_thumbnail_id($post->id));
							} else {
								$imx = wp_get_attachment_image_src(
										get_post_thumbnail_id($post->id), $this->imageSettings['image_source_featured'], false
								);
								if ( $imx !== false && isset($imx[0]) )
									$im = $imx[0];
							}
							break;
						case "content":
                            if ( $sd['showdescription'] == 0 )
                                $content = get_post_field('post_content', $post->id);
                            else
                                $content = $post->content;
                            $content = apply_filters('the_content', $content);

							$im = wpdreams_get_image_from_content( $content, 1 );
							if ( is_multisite() ) {
								$im = str_replace( home_url(), network_home_url(), $im );
							}
							break;
						case "excerpt":
							$im = wpdreams_get_image_from_content( $post->excerpt, 1 );
							if ( is_multisite() ) {
								$im = str_replace( home_url(), network_home_url(), $im );
							}
							break;
						case "screenshot":
							$im = 'http://s.wordpress.com/mshots/v1/' . urlencode( get_permalink( $post->id ) ) .
							      '?w=' . $this->imageSettings['image_width'] . '&h=' . $this->imageSettings['image_height'];
							break;
						case "custom":
							if ( $this->imageSettings['image_custom_field'] != "" ) {
                                $val = get_post_meta( $post->id, $this->imageSettings['image_custom_field'], true );
                                if ( $val != null && $val != "" ) {
                                    if ( is_numeric($val) ) {
                                        $im = wp_get_attachment_url( $val );
                                    } else {
                                        $im = $val;
                                    }
                                }
							}
							break;
						case "default":
							if ( $this->imageSettings['image_default'] != "" ) {
								$im = $this->imageSettings['image_default'];
							}
							break;
						default:
							$im = "";
							break;
					}
					if ( $im != null && $im != '' ) {
						break;
					}
				}

				return $im;
			} else {
				return $post->image;
			}
		}

		/**
		 * Returns the context of a phrase within a text.
		 * Uses preg_split method to iterate through strings.
		 *
		 * @param $str string context
		 * @param $needle string context
		 * @param $context int length of the context
		 * @param $maxlength int maximum length of the string in characters
		 * @param $str_length_limit int source string maximum length
         * @param $false_on_no_match bool to return boolean false if no matches were found
		 * @return string
		 */
		public function context_find($str, $needle, $context, $maxlength, $str_length_limit = 25000, $false_on_no_match = false) {
			$haystack = ' '.trim($str).' ';

			// To prevent memory overflow, we need to limit the hay to relatively low count
			$haystack = wd_substr_at_word(ASL_mb::strtolower($haystack), $str_length_limit);
			$needle = ASL_mb::strtolower($needle);

			if ( $needle == "" ) return $str;

			/**
			 * This is an interesting issue. Turns out mb_substr($hay, $start, 1) is very ineffective.
			 * the preg_split(..) method is far more efficient in terms of speed, however it needs much more
			 * memory. In our case speed is the top priority. However to prevent memory overflow, the haystack
			 * is reduced to 10000 characters (roughly 1500 words) first.
			 *
			 * Reference ticket: https://wp-dreams.com/forums/topic/search-speed/
			 * Speed tests: http://stackoverflow.com/questions/3666306/how-to-iterate-utf-8-string-in-php
			 */
			$chrArray = preg_split('//u', $haystack, -1, PREG_SPLIT_NO_EMPTY);
			$hay_length = count($chrArray) - 1;

			if ( $i = ASL_mb::strpos($haystack, $needle) ) {
				$start=$i;
				$end=$i;
				$spaces=0;

				while ($spaces < ((int) $context/2) && $start > 0) {
					$start--;
					if ($chrArray[$start] == ' ') {
						$spaces++;
					}
				}

				while ($spaces < ($context +1) && $end < $hay_length) {
					$end++;
					if ($chrArray[$end] == ' ') {
						$spaces++;
					}
				}

				while ($spaces < ($context +1) && $start > 0) {
					$start--;
					if ($chrArray[$start] == ' ') {
						$spaces++;
					}
				}

				$str_start = ($start - 1) < 0 ? 0 : ($start -1);
				$str_end = ($end - 1) < 0 ? 0 : ($end -1);

				$result = trim( ASL_mb::substr($str, $str_start, ($str_end - $str_start)) );

				// Somewhere inbetween..
				if ( $start != 0 && $end < $hay_length )
					return "..." . $result . "...";

				// Beginning
				if ( $start == 0 && $end < $hay_length )
					return $result . "...";

				// End
				if ( $start != 0 && $end == $hay_length )
					return "..." . $result;

				// If it is too long, strip it
				if ( ASL_mb::strlen($result) > $maxlength)
					return wd_substr_at_word( $result, $maxlength ) . "...";

				// Else, it is the whole
				return $result;

			} else {
				if ( $false_on_no_match )
					return false;

				// If it is too long, strip it
				if ( ASL_mb::strlen($str) > $maxlength)
					return wd_substr_at_word( $str, $maxlength ) . "...";

				return $str;
			}
		}

		/**
		 * An empty function to override individual shortcodes, this must be public
		 *
		 * @return string
		 */
		public function return_empty_string() {
			return "";
		}

	}
}