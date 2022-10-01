<?php

if(!defined('ABSPATH'))
    exit;

if(!class_exists('WP_404_Auto_Redirect'))
    return;

trait WP_404_Auto_Redirect_Ajax {
    
    function preview(){
        if(!current_user_can('administrator'))
            wp_die();
        
        $request = urldecode(htmlspecialchars($_POST['request']));
        if(empty($request))
            wp_die();

        $this->request($request, true);
        wp_die();
    }
}