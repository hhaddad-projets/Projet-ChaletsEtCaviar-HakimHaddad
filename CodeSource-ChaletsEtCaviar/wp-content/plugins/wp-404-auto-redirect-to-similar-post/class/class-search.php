<?php

if(!defined('ABSPATH'))
    exit;

if(!class_exists('WP_404_Auto_Redirect_Search')):

class WP_404_Auto_Redirect_Search {
    
    function sql($args, $query){
        global $wpdb;
        
        $args = wp_parse_args($args, array(
            'keywords' 	=> false,
            'mode'      => 'post',
            'post_type' => false,
            'taxonomy'  => false
        ));
        
        if(!$args['keywords']){
            return array(
                'result' => array(
                    'score' => 0
                ),
                'dump' => false
            );
        }
        
        // Mode: Post
        if($args['mode'] == 'post'){
            
            // Post Type Args not set && All Post Types are excluded in settings. Early Stop.
            if(!$args['post_type'] && empty($query['settings']['rules']['include']['post_types'])){
                return array(
                    'result' => array(
                        'score' => 0
                    ),
                    'dump' => false
                );
            }

            $sql = "SELECT p.ID, ";
            
            if(is_array($args['keywords'])){
                
                foreach($args['keywords'] as $k){
                    $sql .= "
                    if(INSTR(LCASE(p.post_name), '" . $k . "'), 1, 0) + ";
                }
                
            }else{
                
                $sql .= "
                if(INSTR(LCASE(p.post_name), '" . $args['keywords'] . "'), 1, 0) + ";
                
            }

            $sql .= "0 AS score FROM " . $wpdb->posts . " AS p";
                
                if($query['settings']['rules']['exclude']['post_meta']){
                
                    $sql .= "
                    INNER JOIN " . $wpdb->postmeta . " AS pm ON(p.ID = pm.post_id)
                    WHERE p.post_status = 'publish' AND (pm.meta_key = 'wp404arsp_no_redirect' AND pm.meta_value != '1') ";
                    
                }else{
                
                    $sql .= "
                    WHERE p.post_status = 'publish' ";
                    
                }
                
                if($args['post_type'] != 'any' && $args['post_type'] != array('any')){
                    
                    $get_post_types = array();
                    
                    // Load Settings Post Types
                    if(!$args['post_type']){
                    
                        $get_post_types = $query['settings']['rules']['include']['post_types'];
                        
                    // Post Type Array
                    }elseif(is_array($args['post_type']) && !empty($args['post_type'])){
                        
                        $get_post_types = $args['post_type'];
                    
                    // Single Post Type
                    }elseif(is_string($args['post_type'])){
                        
                        $get_post_types[] = $args['post_type'];
                        
                    }
                    
                    if(!empty($get_post_types)){
                    
                        $post_types = array();
                        
                        foreach($get_post_types as $pt){
                            $post_types[] = "
                            p.post_type = '" . $pt . "'";
                        }
                        
                        $sql .= 'AND 
                        (' . implode(' OR ', $post_types) . ')';
                        
                    }
                    
                }
                
            $sql .= " 
            ORDER BY score DESC, post_modified DESC LIMIT 1";
        
        }
        
        // Mode: Term
        elseif($args['mode'] == 'term'){
        
            // Taxonomy Args not set && All Taxonomies are excluded in settings. Early Stop.
            if(!$args['taxonomy'] && (empty($query['settings']['rules']['include']['taxonomies']) || $query['settings']['rules']['disable']['taxonomies'])){
                return array(
                    'result' => array(
                        'score' => 0
                    ),
                    'dump' => false
                );
            }
            
            $sql = "SELECT t.term_id, ";
            
            if(is_array($args['keywords'])){
                
                foreach($args['keywords'] as $k){
                    $sql .= "
                    if(INSTR(LCASE(t.slug), '" . $k . "'), 1, 0) + ";
                }
                
            }else{
                
                $sql .= "
                if(INSTR(LCASE(t.slug), '" . $args['keywords'] . "'), 1, 0) + ";
                
            }

            $sql .= "
            0 AS score FROM " . $wpdb->terms . " AS t";
            
            $sql .= "
            INNER JOIN " . $wpdb->term_taxonomy . " AS tt ON(t.term_id = tt.term_id)";
            
            if($query['settings']['rules']['exclude']['term_meta']){
                $sql .= "
                INNER JOIN " . $wpdb->termmeta . " AS tm ON(t.term_id = tm.term_id)";
            }
            
                if($args['taxonomy'] != 'any' && $args['taxonomy'] != array('any')){
                    
                    $get_taxonomies = array();
                    
                    // Load Settings Post Types
                    if(!$args['taxonomy']){
                    
                        $get_taxonomies = $query['settings']['rules']['include']['taxonomies'];
                        
                    // Post Type Array
                    }elseif(is_array($args['taxonomy']) && !empty($args['taxonomy'])){
                        
                        $get_taxonomies = $args['taxonomy'];
                    
                    // Single Post Type
                    }elseif(is_string($args['taxonomy'])){
                        
                        $get_taxonomies[] = $args['taxonomy'];
                        
                    }
                    
                    if(!empty($get_taxonomies)){
                    
                        $taxonomies = array();
                        
                        foreach($get_taxonomies as $tax){
                            $taxonomies[] = "
                            tt.taxonomy = '" . $tax . "'";
                        }
                        
                        $sql .= '
                        WHERE (' . implode(' OR ', $taxonomies) . ')';
                        
                        if($query['settings']['rules']['exclude']['term_meta']){
                            $sql .= "
                            AND (tm.meta_key = 'wp404arsp_no_redirect' AND tm.meta_value != '1')";
                        }
                        
                    }
                    
                }
            
            $sql .= "
            ORDER BY score DESC LIMIT 1";
            
        }
        
        $search = $wpdb->get_row($sql, 'ARRAY_A');
        
        // init Result
        $result = array();
        
        // SQL Dump
        $result['sql'] = $sql;
        
        // Post ID
        if(isset($search['ID']) && !empty($search['ID']))
            $result['post_id'] = (int) $search['ID'];
        
        // Term ID
        if(isset($search['term_id']) && !empty($search['term_id']))
            $result['term_id'] = (int) $search['term_id'];
        
        // Score
        $result['score'] = 0;
        if(isset($search['score']) && !empty($search['score']))
            $result['score'] = (int) $search['score'];
        
        // Return Result
        return $result;
    }
    
}

wp404arsp()->search = new WP_404_Auto_Redirect_Search();

endif;

function wp404arsp_search($args, $query){
	return wp404arsp()->search->sql($args, $query);
}