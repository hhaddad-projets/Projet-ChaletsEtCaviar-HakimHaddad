<?php

if(!defined('ABSPATH'))
    exit;

if(!class_exists('WP_404_Auto_Redirect_Engines')){

class WP_404_Auto_Redirect_Engines {
    
    public $get_engines = array();
    
    function __construct(){
        
        // Engines: Register
        add_action('wp404arsp/search/init',                             array($this, 'register_engines'),               1, 2);
        
        // Engines: Results
        add_filter('wp404arsp/search/engine/default_fix_url',           array($this, 'engine_default_fix_url'),         1, 3);
        add_filter('wp404arsp/search/engine/default_direct',            array($this, 'engine_default_direct'),          1, 3);
        add_filter('wp404arsp/search/engine/default_post',              array($this, 'engine_default_post'),            1, 3);
        add_filter('wp404arsp/search/engine/default_term',              array($this, 'engine_default_term'),            1, 3);
        add_filter('wp404arsp/search/engine/default_post_fallback',     array($this, 'engine_default_post_fallback'),   1, 3);
        
	}
    
    function register_engines(){
        
        $this->register_engine(array(
            'name'      => 'Default: Fix URL',
            'slug'      => 'default_fix_url',
            'weight'    => 100,
            'primary'   => true
        ));
        
        $this->register_engine(array(
            'name'      => 'Default: Direct Match',
            'slug'      => 'default_direct',
            'weight'    => 100,
            'primary'   => false
        ));
        
        $this->register_engine(array(
            'name'      => 'Default: Search Post',
            'slug'      => 'default_post',
            'weight'    => 100,
            'primary'   => true
        ));
        
        $this->register_engine(array(
            'name'      => 'Default: Search Term',
            'slug'      => 'default_term',
            'weight'    => 100,
            'primary'   => false
        ));
        
        $this->register_engine(array(
            'name'      => 'Default: Post Fallback',
            'slug'      => 'default_post_fallback',
            'weight'    => 100,
            'primary'   => true
        ));
        
    }
    
    function register_engine($args){
        
        $args = wp_parse_args($args, array(
            'name'      => false,
            'slug'      => false,
            'weight'    => 100,
            'primary'   => false
        ));
        
        if(!$args['name'])
            return;
        
        if(!$args['slug'])
            $args['slug'] = wp404arsp_sanitize($args['name'], '_');
        
        if(empty($args['slug']))
            return;
        
        $engine = apply_filters('wp404arsp/define/engine/' . $args['slug'], $args);
        if(!$engine)
            return;
        
        $this->get_engines[] = $args;
        
    }
    
    
    /*
	*  Engines: Deregister
	*
	*/
    
    function deregister_engine($slug){
        
        if(empty($this->get_engines))
            return;
        
        $reset = false;
        
        // Engines
        foreach($this->get_engines as $e => $engine){
        
            if($engine['slug'] != $slug)
                continue;
            
            // Groups: Engine
            wp404arsp_deregister_groups_engine($slug);
            
            // Engine
            unset($this->get_engines[$e]);
            $reset = true;
            break;
            
        }
        
        if($reset)
            $this->get_engines = array_values($this->get_engines);
        
    }
    
    function get_engine_by_slug($slug){
        
        if(empty($this->get_engines))
            return false;
        
        foreach($this->get_engines as $engine){
            if($engine['slug'] != $slug)
                continue;
            
            return $engine;
        }
        
        return false;
        
    }
    
    function engine_exists($slug){
    
        return $this->get_engine_by_slug($slug);
        
    }
    
    /*
    *  Engine: Fix URL
    *
    *  Condition: If Pagination Regex exists in the Requested URL.
    *  If so, redirect to the same URL without the Pagination.
    *
    */

    function engine_default_fix_url($result, $query, $group){
        
        // Fix URL '/p=6' instead of '/?p=6'
        if(preg_match('#/(?<param>p)=(?<val>[0-9]+)/?$#i', $query['request']['url'], $args)){
            
            if(get_post($args['val'])){
                return array(
                    'url'   => get_permalink($args['val']),
                    'score' => 1,
                    'why'   => "Fix Requested URL. Sending redirection to the correct permalink."
                );
            }
            
        }
        
        // Fix Pagination
        elseif(preg_match('#/(?<slug>page|paged)/(?<page>[0-9]+)/?$#i', $query['request']['url'], $pagination)){
            $url = home_url() . str_replace($pagination[0], '', $query['request']['url']);
            
            return array(
                'url'   => $url,
                'score' => 1,
                'why'   => "Pagination found in the Requested URL. Sending redirection to the same URL without Pagination."
            );
        }
        
        // Pagination not found
        return "No Fix to apply in the Requested URL.";
        
    }


    /*
    *  Engine: Direct Match
    *
    *  Condition: None.
    *  Use get_page_by_path() to find the Post with the exact Name.
    *
    */

    function engine_default_direct($result, $query, $group){
        
        // No Post Types available
        if(!$post_types = wp404arsp_get_post_types($query['settings']))
            return "All Post Types are disabled in settings.";
        
        // init Found
        $found = false;
        
        // Get keywords
        $keywords = $query['request']['keywords']['array'];
        
        // Add all keywords combined
        $keywords[] = $query['request']['keywords']['all'];
        
        foreach($keywords as $k){
            
            // Not found: continue
            if(!$post = get_page_by_path($k, 'object', $post_types))
                continue;
            
            $found = true;
            $score = count(explode('-', $k));
            break;
            
        }
        
        // Found
        if($found){
            
            // Found but post Status not 'published'. Stop direct match
            if(get_post_status($post->ID) != 'publish'){
                
                return "Post Status not published. Stop direct match.";
                
            }
            
            // Post Meta 'wp404arsp_no_redirect = 1'. Stop direct match
            elseif($query['settings']['rules']['exclude']['post_meta'] && get_post_meta($post->ID, 'wp404arsp_no_redirect', true) == '1'){
                
                return "Exluded Post Meta Set. Stop direct match.";
                
            }
            
            // Everything is Okay.
            else{
                
                return array(
                    'score' => $score,
                    'url' 	=> get_permalink($post->ID),
                    'why'   => "Part of the requested URL already exist as Post of the Post Type: <strong>" . get_post_type($post->ID) . "</strong>"
                );
            
            }
            
        }
        
        // Not Found
        else{
            
            return "No Direct Match in any Post Types.";
            
        }
        
    }


    /*
    *  Engine: Search Post
    *
    *  Condition: None.
    *  Search similar Post in any available Post Types.
    *
    */

    function engine_default_post($result, $query, $group){
        
        // No Post Types available
        if(!$post_types = wp404arsp_get_post_types($query['settings']))
            return "All Post Types are disabled in settings.";
        
        $wp_query = false;
        
        // Post_Type found in WP_Query
        if(!wp404arsp_is_empty($query['request']['wp_query']['post_type'])){
            
            $wp_query = true;
            $wp_query_post_type = $query['request']['wp_query']['post_type'];

            // Post_Type found in WP_Query is disabled in settings. Disable this specific search
            if(!in_array($wp_query_post_type, wp404arsp_get_post_types($query['settings'])))
                $wp_query = false;
            
        }
        
        // Keywords
        $keywords = explode('-', $query['request']['keywords']['all']);
        
        // Search Args
        $args = array(
            'keywords'  => $keywords,   // Add keywords
            'mode'      => 'post'       // Search for Post
        );
        
        if($wp_query)
            $args['post_type'] = $wp_query_post_type; // Add specific Post Type Search
        
        // Run Search
        $search = wp404arsp_search($args, $query);
        
        // Found
        if($search['score'] > 0){
            
            // Reason
            $why = "Similar Post of Post Type <strong>" . get_post_type($search['post_id']) . "</strong> was found.";
            
            // Change reason if Post_Type found in WP_Query
            if($wp_query)
                $why = "Similar Post found in the WP Query: Post Type <strong>" . $wp_query_post_type . "</strong>.";
            
            // Return result
            return array(
                'score' => $search['score'],
                'url'   => get_permalink($search['post_id']),
                'why'	=> $why
            );
            
        }
        
        // Not found
        else{
            
            // Reason
            $why = "No similar Post found.";
            
            // Change reason if Post_Type found in WP_Query
            if($wp_query){
                $why = "No Post found in the WP Query: Post Type <strong>" . $wp_query_post_type . "</strong>.";
                
                // Set Query Var for Engine: WP Query Fallback
                set_query_var('wp404arsp_engine_default_post', $wp_query_post_type);
                
            }
            
            return $why;
            
        }
    }


    /*
    *  Engine: Search Term
    *
    *  Condition: None.
    *  Search similar Term in any available Taxonomies.
    *
    */

    function engine_default_term($result, $query, $group){
        
        // Taxonomies Disabled
        if($query['settings']['rules']['disable']['taxonomies'])
            return "Taxonomies Disabled in settings.";
        
        $wp_query = false;
        
        // Taxonomy found in WP_Query
        if(!wp404arsp_is_empty($query['request']['wp_query']['taxonomy'])){
            
            $wp_query = true;
            $wp_query_taxonomy = $query['request']['wp_query']['taxonomy'];
            
            // Taxonomy found in WP_Query is disabled in settings. Disable this specific search
            if(!in_array($wp_query_taxonomy, wp404arsp_get_taxonomies($query['settings'])))
                $wp_query = false;
            
        }
        
        // Keywords
        $keywords = explode('-', $query['request']['keywords']['all']);
        
        // Search Args
        $args = array(
            'keywords'  => $keywords,   // Add keywords
            'mode'      => 'term'       // Search for Term
        );
        
        if($wp_query)
            $args['taxonomy'] = $wp_query_taxonomy; // Add specific Taxonomy Search
        
        // Run Search
        $search = wp404arsp_search($args, $query);
        
        // Found
        if($search['score'] > 0){
            
            // Get Term
            $term = get_term($search['term_id']);
            
            // Reason
            $why = "Similar Term was found in the Taxonomy <strong>" . $term->taxonomy . "</strong>.";
            
            // Change reason if Taxonomy found in WP_Query
            if($wp_query)
                $why = "Similar Term found in the WP Query: Taxonomy <strong>" . $wp_query_taxonomy . "</strong>.";
            
            return array(
                'score' => $search['score'], 
                'url' 	=> get_term_link($search['term_id']),
                'why'	=> $why
            );
            
        }
        
        // Not found
        else{
            
            // Reason
            $why = "No similar Term was found in Taxonomies.";
            
            // Change reason if Taxonomy found in WP_Query
            if($wp_query)
                $why = "No Term found in the WP Query: Taxonomy <strong>" . $wp_query_taxonomy . "</strong>.";
            
            return $why;
            
        }
        
    }


    /*
    *  Engine: Post Fallback
    *
    *  Condition: If the Post Type is set in the current WP_Query AND the engine "WP Query: Post Type" failed to found a similar Post inside it.
    *  If the engine "WP Query: Post Type" failed, it will set a Query Var 'wp404arsp_engine_wpq_pt'. This engine will use it to fallback to the Post Type Archive.
    *
    */

    function engine_default_post_fallback($result, $query, $group){
        
        // Query Var: 'wp404arsp_engine_default_post' set by the engine "Default: WP Query" because it failed.
        $post_type = get_query_var('wp404arsp_engine_default_post', false);
        
        if($post_type){
            
            // Get Post Type Archive Link
            $url = get_post_type_archive_link($post_type);
            if(empty($url))
                $url = home_url();
            
            return array(
                'url'   => $url,
                'score' => 1,
                'why'   => "WP Query Fallback: Redirecting to Post Type <strong>" . $post_type . "</strong> Archive."
            );
            
        }
        
        // No Query Var. Stop.
        else{
            
            return "No Post Type in the WP Query.";
            
        }
    }
    
}

wp404arsp()->engines = new WP_404_Auto_Redirect_Engines();

}

function wp404arsp_register_engine($engine){
    
	return wp404arsp()->engines->register_engine($engine);
    
}

function wp404arsp_deregister_engine($slug){
    
	return wp404arsp()->engines->deregister_engine($slug);
    
}

function wp404arsp_get_engine_by_slug($slug){
    
	return wp404arsp()->engines->get_engine_by_slug($slug);
    
}

function wp404arsp_engine_exists($slug){
    
	return wp404arsp()->engines->engine_exists($slug);
    
}