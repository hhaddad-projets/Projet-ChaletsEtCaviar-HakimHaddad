<?php

if(!defined('ABSPATH'))
    exit;

if(!class_exists('WP_404_Auto_Redirect_Settings')):

class WP_404_Auto_Redirect_Settings {

    function get(){
        
        $option = get_option('wp404arsp_settings');
        
        // 0.9.0.2 Deprecated compatibility
        // ---------------------------------------
        if(!wp404arsp_is_empty($option['rules']['redirection']['exclude'])){
            $option['rules']['exclude'] = $option['rules']['redirection']['exclude'];
            unset($option['rules']['redirection']['exclude']);
        }
        
        if(!wp404arsp_is_empty($option['rules']['redirection']['disable'])){
            $option['rules']['disable'] = $option['rules']['redirection']['disable'];
            unset($option['rules']['redirection']['disable']);
        }
        
        if(isset($option['rules']['redirection']))
            unset($option['rules']['redirection']);
        // ---------------------------------------
        
        // Defaults
        $settings = wp404arsp_parse_args_recursive($option, array(
            'debug'     => null,
            'headers'   => null,
            'log'       => null,
            'method'    => 301,
            'priority'  => 999,
            'fallback'  => array(
                'type'      => 'home',
                'url'       => home_url(),
                'home_url'  => home_url(),
            ),
            'rules'     => array(
                'include'   => array(
                    'post_types'    => array(),
                    'taxonomies'    => array(),
                ),
                'exclude'   => array(
                    'post_meta'     => null,
                    'term_meta'     => null,
                    'post_types'    => array(),
                    'taxonomies'    => array(),
                ),
                'disable'   => array(
                    'taxonomies'    => null
                )
            )
        ));
        
        // Include
        $settings['rules']['include']['post_types'] = wp404arsp_get_post_types($settings);
        $settings['rules']['include']['taxonomies'] = wp404arsp_get_taxonomies($settings);
        
        // Falback
        if($settings['fallback']['type'] == 'home')
            $settings['fallback']['url'] = home_url();
        
        // Headers
        if(((int)$settings['method'] != 301) && ((int)$settings['method'] != 302))
            $settings['method'] = 301;
        
        // Return
        return $settings;
        
    }
    
}

wp404arsp()->settings = new WP_404_Auto_Redirect_Settings();

endif;

function wp404arsp_settings_get(){
    
    return wp404arsp()->settings->get();
    
}