<?php
/**
 * Plugin Name: WP 404 Auto Redirect to Similar Post
 * Description: Automatically Redirect any 404 page to a Similar Post based on the Title, Post Type & Taxonomy using 301 Redirects!
 * Version: 	1.0.2
 * Author: 		hwk-fr
 * Author URI: 	https://hwk.fr
 * Text Domain: wp-404-auto-redirect
 */
 
if(!defined('ABSPATH'))
    exit;

if(!defined('WP404ARSP_PATH'))
    define('WP404ARSP_PATH', plugin_dir_path(__FILE__));

if(!defined('WP404ARSP_FILE'))
    define('WP404ARSP_FILE', __FILE__);

if(!class_exists('WP_404_Auto_Redirect')):

// Traits
include_once(WP404ARSP_PATH . 'includes/admin.php');
include_once(WP404ARSP_PATH . 'includes/ajax.php');
include_once(WP404ARSP_PATH . 'includes/debug.php');

class WP_404_Auto_Redirect {
    
    Use WP_404_Auto_Redirect_Admin;
    Use WP_404_Auto_Redirect_Ajax;
    Use WP_404_Auto_Redirect_Debug;
    
    function init(){
        
        // Helpers
        include_once(WP404ARSP_PATH . 'includes/helpers.php');
        
        // Classes
        include_once(WP404ARSP_PATH . 'class/class-engines.php');
        include_once(WP404ARSP_PATH . 'class/class-groups.php');
        include_once(WP404ARSP_PATH . 'class/class-search.php');
        include_once(WP404ARSP_PATH . 'class/class-settings.php');
        
        // WP: Admin
        add_action('admin_menu',                                array($this, 'admin_menu'),                     10, 1);
        add_filter('plugin_action_links',                       array($this, 'admin_link'),                     10, 2);
        add_action('admin_init',                                array($this, 'admin_settings'),                 10, 1);
        add_action('admin_enqueue_scripts',                     array($this, 'admin_scripts'),                  10, 1);
        
        // WP: Run
        add_action('template_redirect',                         array($this, 'run'),                $this->priority());
        
        // Preview
        add_action('wp_ajax_wp404arsp_ajax_preview',            array($this, 'preview'),                        1, 1);
        
        // Log
        add_action('wp404arsp/after_redirect',                  array($this, 'log'),                            1, 1);
        
        /**
         ****************************************************************************
         *  Filters & Actions Fire Sequence:
         ****************************************************************************
         *  
         *  filter('wp404arsp/init',                    true,       $request        )
         *  
         *  action('wp404arsp/search/init',             $query                      )
         *  filter('wp404arsp/search/group',            $group,     $query          )
         *  filter('wp404arsp/search/query',            $query                      )
         *  filter('wp404arsp/search/engine/{engine}',  $result,    $query, $group  )
         *  filter('wp404arsp/search/results',          $query                      )
         *  filter('wp404arsp/search/redirect',         $redirect,  $query          )
         *  
         *  filter('wp404arsp/redirect',                $query                      )
         *  action('wp404arsp/after_redirect',          $query                      )
         *  
         ****************************************************************************
         */
        
    }
    
    function priority(){
        
        $priority = 999;
        $wp404arsp_settings = get_option('wp404arsp_settings');
        if(isset($wp404arsp_settings['priority']))
            $priority = (int) $wp404arsp_settings['priority'];
        
        return $priority;
        
    }
    
    function run(){
        
        // is 404
        if(!is_404() || wp_doing_ajax() || is_admin() || wp404arsp_is_empty($_SERVER['REQUEST_URI']))
            return;
        
        // Admin Ajax
        if(!wp404arsp_is_empty($_SERVER['SCRIPT_URI']) && $_SERVER['SCRIPT_URI'] == admin_url('admin-ajax.php'))
            return;
        
        // XMLRequest
        if(!wp404arsp_is_empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest')
            return;
        
        // Sanitize Request
        $request = urldecode(esc_url_raw($_SERVER['REQUEST_URI']));
        if(empty($request))
            return;
        
        $this->request($request);
        
    }
    
    function request($request, $preview = false){
        
        // Pathinfo
        $path = pathinfo(strtok($request, '?'));
        $path['dirname'] = str_replace('\\', '/', $path['dirname']);
        
        // Params
        $params = array();
        $request_parts = parse_url($request);
        if(!wp404arsp_is_empty($request_parts['query']))
            wp_parse_str($request_parts['query'], $params);
        
        // Query
        $query = array(
            'preview'   => $preview,
            'request'   => array(
                'url'       => $request,
                'referrer'  => isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : false,
                'dirname'   => $path['dirname'],
                'filename'  => $path['filename'],
                'extension' => (!wp404arsp_is_empty($path['extension']) ? $path['extension'] : ''),
                'params'    => $params,
                'keywords'  => array(
                    'all'   => '',
                    'array' => array()
                ),
            )
        );
        
        // Remove Params in URL
        $url = strtok($query['request']['url'], '?');
        
        // Keywords: Sanitize
        $query['request']['keywords']['all'] = str_replace('.' . $query['request']['extension'], '', $url);
        $query['request']['keywords']['all'] = wp404arsp_sanitize($query['request']['keywords']['all']);
        
        // Keywords: Explode Array
        $keywords = explode('/', trim($url, '/'));
        foreach($keywords as $keyword){
            if(!wp404arsp_is_empty($query['request']['extension']))
                $keyword = str_replace('.' . $query['request']['extension'], '', $keyword);
            
            $query['request']['keywords']['array'][] = wp404arsp_sanitize($keyword);
        }
        
        // Keywords: Reverse for priority (last part is probably the most important)
        $query['request']['keywords']['array'] = array_reverse($query['request']['keywords']['array']);
        
        // WP Query
        global $wp_query;
        
        if(isset($wp_query->query_vars)){
            $query_vars = $wp_query->query_vars;
            
            // WP Query: Post Type found
            if(!wp404arsp_is_empty($query_vars['post_type']) && !wp404arsp_is_empty($query_vars['name'])){
                $query['request']['wp_query']['post_type'] = $query_vars['post_type'];
                $query['request']['wp_query']['name'] = $query_vars['name'];
            }
            
            // WP Query: Taxonomy found
            if(!wp404arsp_is_empty($query_vars['taxonomy']) && !wp404arsp_is_empty($query_vars['term'])){
                $query['request']['wp_query']['taxonomy'] = $query_vars['taxonomy'];
                $query['request']['wp_query']['term'] = $query_vars['term'];
            }
        }
        
        // Settings
        $query['settings'] = wp404arsp_settings_get($query);
        
        // Filter init
        if(!apply_filters('wp404arsp/init', true, $query))
            return;
        
        // Search
        $this->search($query);
        
    }
    
    function search($query){
        
        // init Engines & Groups
        do_action('wp404arsp/search/init', $query);
        
        // add Engines & Groups
        $query['engines'] = wp404arsp()->engines->get_engines;
        $query['groups'] = wp404arsp()->groups->get_groups;
        
        // init Search
        $query['search'] = array();
        
        // init Search Group
        $query['search']['group'] = apply_filters('wp404arsp/search/group', 'default', $query);
        
        // Filter Query
        $query = apply_filters('wp404arsp/search/query', $query);
        
        // Run Search
        if(!empty($query['groups']) && !empty($query['search']['group'])){
            
            foreach($query['groups'] as $g => $group){
                
                if($group['slug'] != $query['search']['group'])
                    continue;
            
                if(empty($query['engines']) || empty($query['groups'][$g]['engines']))
                    break;
                
                foreach($query['groups'][$g]['engines'] as $e_slug){
                    
                    if(!$engine = wp404arsp_get_engine_by_slug($e_slug))
                        continue;
                    
                    if(!$result = apply_filters('wp404arsp/search/engine/' . $engine['slug'], false, $query, $group))
                        continue;
                    
                    $result = wp404arsp_set_result($result, $engine);
                    if(!$result)
                        continue;
                    
                    $query['search']['results'][] = $result;
                
                    // Stop Search if Engine's Primary = true AND Score > 0
                    if($result['score'] > 0 && $result['primary'])
                        break;
                    
                }
                
                break;
            
            }
            
        }
        
        // Filter Search Results
        $query['search'] = apply_filters('wp404arsp/search/results', $query['search'], $query);
        
        // init Redirection
        $query['redirect'] = false;
        
        // Redirection by highest score
        if(!empty($query['search']['results'])){
            $s=0; foreach($query['search']['results'] as $r){
                
                if($r['score'] > $s)
                    $query['redirect'] = $r;
                
                // Stop if engine = primary
                if($r['score'] > 0 && $r['primary'] === true)
                    break;
                
                $s = $r['score'];
                
            }
        }
        
        // Redirection fallback
        if(!$query['redirect']){
            
            $fallback = $query['settings']['fallback']['url'];
            
            if($query['settings']['fallback']['type'] == 'disabled')
                $fallback = false;
            
            $engine = array(
                'name' => 'None',
                'slug' => 'none'
            );
            
            $query['redirect'] = wp404arsp_set_result(array(
                'url'   => $fallback,
                'score' => 0,
                'why'   => "Nothing found. Applying fallback behavior."
            ), $engine);
            
        }
        
        // Filter Search Redirect
        $query['redirect'] = apply_filters('wp404arsp/search/redirect', $query['redirect'], $query);
        
        // Redirect
        $this->redirect($query);
        
    }

    function redirect($query){
        
        // Filter: wp404arsp/redirect
        $query = apply_filters('wp404arsp/redirect', $query);
        
        // Debug
        if(is_user_logged_in() && current_user_can('administrator') && ($query['settings']['debug'] || $query['preview']))
            return $this->debug($query);
        
        // Fallback: 404
        if(!$query['redirect']['url'])
            return;
        
        // Redirect
        $this->redirect_to($query);
        
        return;
        
    }

    function redirect_to($query){
        
        // Copy/paste from legacy WP_Redirect function()
        // File: wp-includes/pluggable.php
        
        // Added: 'WP-404-Auto-Redirect: true' header
        // Added: 'wp404arsp/after_redirect' action
        // Added: PHP exit;
        
        global $is_IIS;
        
        $status = $query['settings']['method'];
        $location = apply_filters('wp_redirect', $query['redirect']['url'], $status);
        $status = apply_filters('wp_redirect_status', $status, $location);
     
        if(!$location)
            return false;
     
        $location = wp_sanitize_redirect($location);
     
        if(!$is_IIS && PHP_SAPI != 'cgi-fcgi')
            status_header($status);
     
        header("Location: $location", true, $status);
        
        // Expose Headers
        if(current_user_can('administrator') && $query['settings']['headers']){
            header('WP-404-Auto-Redirect: true');
            
            if(isset($query['search']['group']))
                header('WP-404-Auto-Redirect-Group: ' . $query['search']['group']);
            
            if(isset($query['redirect']['engine']))
                header('WP-404-Auto-Redirect-Engine: ' . $query['redirect']['engine']);
            
            if(isset($query['redirect']['primary']))
                header('WP-404-Auto-Redirect-Primary: ' . $query['redirect']['primary']);
            
            if(isset($query['redirect']['engine']))
                header('WP-404-Auto-Redirect-Score: ' . $query['redirect']['engine']);
            
            if(isset($query['redirect']['why']))
                header('WP-404-Auto-Redirect-Why: ' . strip_tags($query['redirect']['why']));
        }
        
        // Action: wp404arsp/after_redirect
        do_action('wp404arsp/after_redirect', $query);
        
        exit;
        
    }
    
    function log($query){
        
        if(empty($query['settings']['log']) || !WP_DEBUG || !WP_DEBUG_LOG)
            return;
        
        $request_url = home_url() . $query['request']['url'];
        $redirect = $query['redirect']['url'];
        $group = $query['search']['group'];
        $engine = $query['redirect']['engine'];
        $score = $query['redirect']['score'];
        $why = strip_tags($query['redirect']['why']);
        
        // Cloudflare Fix
        if(isset($_SERVER["HTTP_CF_CONNECTING_IP"]))
            $_SERVER['REMOTE_ADDR'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
        
        $ip = $_SERVER['REMOTE_ADDR'];
        
        error_log('WP 404 Auto Redirect: ' . $request_url . ' => ' . $redirect . ' (Group: ' . $group . ' | Engine: ' . $engine . ' | Score: ' . $score . ' | Why: ' . $why . ' | IP: ' . $ip . ')');
        
    }
    
}

function wp404arsp(){
	global $wp404arsp;

	if(isset($wp404arsp))
        return $wp404arsp;
    
    $wp404arsp = new WP_404_Auto_Redirect();
    $wp404arsp->init();

	return $wp404arsp;
	
}

// init
wp404arsp();

endif;