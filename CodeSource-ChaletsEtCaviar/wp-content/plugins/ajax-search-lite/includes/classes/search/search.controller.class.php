<?php
/* Prevent direct access */
defined('ABSPATH') or die("You can't access this file directly.");

if (!class_exists('asl_searchController')) {
    /**
     * Class asl_searchController
     *
     * A controller slash wrapper class for the whole search process.
     *
     */
    class asl_searchController {
        /*
         * Results
         */
        private $results;

        /*
         * Array of phrases of all synonym variations
         */
        private $finalPhrases = array();

        /*
         * Constructor args
         */
        private $args;

        /*
         * Search Options
         */
        private $so;

        /*
         * Caching Options
         */
        private $co;

        public function __construct( $args ) {

            $defaults = array(
                'phrase' => "",
                'id'    => 0,
                'instance' => null
            );
            $args = wp_parse_args( $args, $defaults );

            $this->args = $args;
        }

        public function search() {
            $this->parseOptions();

            $sd = &$this->args['instance']['data'];
            $s = $this->args['phrase'];
            $id = $this->args['id'];

            $allpageposts = array();
            $pageposts = array();

            do_action('asl_before_search', $s);

            $params = array('data' => $sd, 'options' => $this->so);

            // VC 4.6+ fix: Shortcodes are not loaded in ajax responses
            // class_exists() is mandatory, some PHP versions fail
            if ( class_exists("WPBMap") && method_exists("WPBMap", "addAllMappedShortcodes") )
                WPBMap::addAllMappedShortcodes();

            $_posts = new wpdreams_searchContent($params);
            $pageposts = $_posts->search($s);
            $allpageposts = array_merge($allpageposts, $pageposts);

            do_action('asl_after_pagepost_results', $s, $pageposts);

            $allpageposts = apply_filters('asl_pagepost_results', $allpageposts);


            $results = array_merge(
                $allpageposts
            );

            $results = apply_filters('asl_results', $results);

            do_action('asl_after_search', $s, $results);

            return $results;
        }

        public function kwSuggestions() {
            $sd = &$this->args['instance']['data'];
            $results = array();

            $types = array();
            if ( isset($sd['customtypes']) )
                $types = array_merge($types, $sd['customtypes']);

            $t = new  wpd_keywordSuggest("google", array(
                'maxCount' => w_isset_def( $sd['kw_count'], 10 ),
                'maxCharsPerWord' => w_isset_def($sd['kw_length'], 60),
                'postTypes' => $types,
                'lang' => w_isset_def( $sd['kw_google_lang'], "en" ),
                'overrideUrl' => ''
            ));

            $keywords = $t->getKeywords( trim($this->args['phrase']) );

            if ($keywords != false) {
                $results['keywords'] = $keywords;
                $results['nores'] = 1;
                $results = apply_filters('asl_only_keyword_results', $results);
            }

            return $results;
        }

        private function parseOptions() {
            $sd = &$this->args['instance']['data'];

            $sd['image_options'] = array(
                'show_images' => $sd['show_images'],
                'image_bg_color' => '#FFFFFF',
                'image_transparency' => 1,
                'image_crop_location' => w_isset_def($sd['image_crop_location'], "c"),
                'image_width' => $sd['image_width'],
                'image_height' => $sd['image_height'],
                'image_source1' => $sd['image_source1'],
                'image_source2' => $sd['image_source2'],
                'image_source3' => $sd['image_source3'],
                'image_source4' => $sd['image_source4'],
                'image_source5' => $sd['image_source5'],
                'image_default' => $sd['image_default'],
                'image_source_featured' => $sd['image_source_featured'],
                'image_custom_field' => $sd['image_custom_field']
            );

            // ----------------- Recalculate image width/height ---------------
            switch ($sd['resultstype']) {
                case "horizontal":
                    /* Same width as height */
                    $sd['image_options']['image_width'] = wpdreams_width_from_px($sd['image_options']['hreswidth']);
                    $sd['image_options']['image_height'] = wpdreams_width_from_px($sd['image_options']['hreswidth']);
                    break;
                case "polaroid":
                    $sd['image_options']['image_width'] = intval($sd['preswidth']);
                    $sd['image_options']['image_height'] = intval($sd['preswidth']);
                    break;
                case "isotopic":
                    $sd['image_options']['image_width'] = intval($sd['i_item_width'] * 1.5);
                    $sd['image_options']['image_height'] = intval($sd['i_item_height'] * 1.5);
                    break;
            }

            if (isset($sd['selected-imagesettings'])) {
                $sd['settings-imagesettings'] = $sd['selected-imagesettings'];
            }
            /*if (isset($search) && $sd['exactonly']!=1) {
              $_s = explode(" ", $s);
            }*/
            if (isset($_POST['options'])) {
                if (is_array($_POST['options']))
                    $this->so = $_POST['options'];
                else
                    parse_str($_POST['options'], $this->so);
            }
        }
    }
}