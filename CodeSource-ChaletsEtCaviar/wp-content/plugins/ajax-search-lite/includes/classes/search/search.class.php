<?php
/* Prevent direct access */
defined('ABSPATH') or die("You can't access this file directly.");

if (!class_exists('wpdreams_search')) {
	/**
	 * Search class Abstract
	 *
	 * All search classes should be descendants to this abstract.
	 *
	 * @class       wpdreams_search
	 * @version     1.1
	 * @package     AjaxSearchPro/Abstracts
	 * @category    Class
	 * @author      Ernest Marcinko
	 */
	abstract class wpdreams_search {

		/**
		 * @var array of parameters
		 */
		protected $params;
		/**
		 * @var array of submitted options from the front end
		 */
		protected $options;
		/**
		 * @var int the ID of the current search instance
		 */
		protected $searchId;
		/**
		 * @var array of the current search options
		 */
		protected $searchData;
		/**
		 * @var array of results
		 */
		protected $results;
		/**
		 * @var string the search phrase
		 */
		protected $s;
		/**
		 * @var string the reverse search phrase
		 */
		protected $sr;
		/**
		 * @var array of each search phrase
		 */
		protected $_s;
		/**
		 * @var array of each search phrase in reverse
		 */
		protected $_sr;

        protected $pre_field = '';
        protected $suf_field = '';
        protected $pre_like  = '';
        protected $suf_like  = '';
        protected $imageSettings;

		/**
		 * Create the class
		 *
		 * @param $params
		 */
		function __construct($params) {

			$this->params = $params;

			// Pass the general options
			$options = w_isset_def($params['options'], array());

			// Set a few values for faster usage
			$options['set_exactonly'] = (isset($params['options']['set_exactonly'])?true:false);
			$options['set_intitle'] = (isset($params['options']['set_intitle'])?true:false);
			$options['set_incontent'] = (isset($params['options']['set_incontent'])?true:false);
			$options['set_incomments'] = (isset($params['options']['set_incomments'])?true:false);
			$options['set_inexcerpt'] = (isset($params['options']['set_inexcerpt'])?true:false);
			$options['set_inposts'] = (isset($params['options']['set_inposts'])?true:false);
			$options['set_inpages'] = (isset($params['options']['set_inpages'])?true:false);
			$options['searchinterms'] = (($params['data']['searchinterms']==1)?true:false);
			$options['set_inbpusers'] = (isset($params['options']['set_inbpusers'])?true:false);
			$options['set_inbpgroups'] = (isset($params['options']['set_inbpgroups'])?true:false);
			$options['set_inbpforums'] = (isset($params['options']['set_inbpforums'])?true:false);

			$options['maxresults'] = $params['data']['maxresults'];
			$options['do_group'] = ($params['data']['resultstype'] == 'vertical') ? true : false;

			$this->options = $options;
			$this->searchId = 1;
			$this->searchData = $params['data'];
			if ( isset($this->searchData['image_options']) )
				$this->imageSettings = $this->searchData['image_options'];

		}

		/**
		 * Initiates the search operation
		 *
		 * @param $keyword
		 * @return array
		 */
		public function search($keyword) {

            $this->prepare_keywords($keyword);
			$this->do_search();
			$this->post_process();
			$this->group();

			return is_array($this->results) ? $this->results : array();
		}

        public function prepare_keywords($s) {

		    $keyword = $s;
            $keyword = $this->compatibility($keyword);
            $keyword_rev = ASL_Helpers::reverseString($keyword);

            $this->s = ASL_Helpers::escape( $keyword );
            $this->sr = ASL_Helpers::escape( $keyword_rev );

            /**
             * Avoid double escape, explode the $keyword instead of $this->s
             * Regex to match individual words and phrases between double quotes
             **/
			if ( preg_match_all( '/".*?("|$)|((?<=[\t ",+])|^)[^\t ",+]+/', $keyword, $matches ) ) {
				$this->_s = $this->parse_search_terms(  $matches[0] );
			} else {
				$this->_s = $this->parse_search_terms( explode(" ", $keyword) );
			}
			if ( preg_match_all( '/".*?("|$)|((?<=[\t ",+])|^)[^\t ",+]+/', $keyword_rev, $matches ) ) {
				$this->_sr = $this->parse_search_terms(  array_reverse($matches[0]) );
			} else {
				$this->_sr = $this->parse_search_terms( array_reverse( explode(" ", $keyword_rev ) ) );
			}

			// Reserved for future use
			$min_word_length = 0;

            foreach ($this->_s as $k=>$w) {
                if ( ASL_mb::strlen($w) < $min_word_length ) {
                    unset($this->_s[$k]);
                }
            }

            foreach ($this->_sr as $k=>$w) {
                if ( ASL_mb::strlen($w) < $min_word_length ) {
                    unset($this->_sr[$k]);
                }
            }
        }

        /**
         * Check if the terms are suitable for searching.
         *
         * @param array $terms Terms to check.
         * @return array Terms
         */
        protected function parse_search_terms( $terms ) {
            $checked = array();

            foreach ( $terms as $term ) {
                // keep before/after spaces when term is for exact match
                if ( preg_match( '/^".+"$/', $term ) )
                    $term = trim( $term, "\"'" );
                else
                    $term = trim( $term, "\"' " );

                if ( $term != '' )
                    $checked[] = $term;
            }

            if ( count($checked) > 0 )
                $checked = ASL_Helpers::escape(
                    array_slice(array_unique($checked), 0, 10)
                );

            return $checked;
        }

        /**
         * Converts the keyword to the correct case and sets up the pre-suff fields.
         *
         * @param $s
         * @return string
         */
        protected function compatibility($s) {
            $comp_options   = get_option( 'asl_compatibility' );

            /**
             *  On forced case sensitivity: Let's add BINARY keyword before the LIKE
             *  On forced case in-sensitivity: Append the lower() function around each field
             */
            if ( $comp_options['db_force_case'] === 'sensitivity' ) {
                $this->pre_like = 'BINARY ';
            } else if ( $comp_options['db_force_case'] === 'insensitivity' ) {
                if ( function_exists( 'mb_convert_case' ) )
                    $s = mb_convert_case( $s, MB_CASE_LOWER, "UTF-8" );
                else
                    $s = strtoupper( $s );
                // if no mb_ functions :(

                $this->pre_field .= 'lower(';
                $this->suf_field .= ')';
            }

            /**
             *  Check if utf8 is forced on LIKE
             */
            if ( w_isset_def( $comp_options['db_force_utf8_like'], 0 ) == 1 )
                $this->pre_like .= '_utf8';

            /**
             *  Check if unicode is forced on LIKE, but only apply if utf8 is not
             */
            if ( w_isset_def( $comp_options['db_force_unicode'], 0 ) == 1
                && w_isset_def( $comp_options['db_force_utf8_like'], 0 ) == 0
            )
                $this->pre_like .= 'N';

            return $s;
        }

		/**
		 * The search function
		 */
		protected function do_search() {}

		/**
		 * Post processing abstract
		 */
		protected function post_process() {}

		/**
		 * Grouping abstract
		 */
		protected function group() {}

	}
}