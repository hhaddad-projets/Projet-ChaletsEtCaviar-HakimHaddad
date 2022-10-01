<?php /**
 * @version 1.0
 * @package 
 * @category Core
 * @author wpdevelop
 *
 * @web-site https://wpbookingcalendar.com/
 * @email info@wpbookingcalendar.com 
 * 
 * @modified 2013.10.16
 */

class WPBC_CSS extends WPBC_JS_CSS{

    public function define() {
        
        $this->setType('css');
        
        /*
        // Exmaples of usage Font Avesome: http://fontawesome.io/icons/
        
        $this->add( array(
                            'handle' => 'font-awesome',
                            'src' => WPBC_PLUGIN_URL . 'assets/libs/font-awesome-4.3.0/css/font-awesome.css' ,
                            'deps' => false,
                            'version' => '4.3.0',
                            'where_to_load' => array( 'admin' ),
                            'condition' => false    
                  ) );   
        
        // Exmaples of usage Font Avesome 3.2.1 (benefits of this version - support IE7): http://fontawesome.io/3.2.1/examples/ 
        $this->add( array(
                            'handle' => 'font-awesome',
                            'src' => WPBC_PLUGIN_URL . '/assets/libs/font-awesome/css/font-awesome.css' ,
                            'deps' => false,
                            'version' => '3.2.1',
                            'where_to_load' => array( 'admin' ),
                            'condition' => false    
                  ) );            
        $this->add( array(
                            'handle' => 'font-awesome-ie7',
                            'src' => WPBC_PLUGIN_URL . '/assets/libs/font-awesome/css/font-awesome-ie7.css' ,
                            'deps' => array('font-awesome'),
                            'version' => '3.2.1',
                            'where_to_load' => array( 'admin' ),
                            'condition' => 'IE 7'                               // CSS condition. Exmaple: <!--[if IE 7]>    
                  ) );  
        */
          
    }


    public function enqueue( $where_to_load ) {        
        
        wp_enqueue_style('wpdevelop-bts',       wpbc_plugin_url( '/assets/libs/bootstrap/css/bootstrap.css' ),          array(), '3.3.5.1');
        wp_enqueue_style('wpdevelop-bts-theme', wpbc_plugin_url( '/assets/libs/bootstrap/css/bootstrap-theme.css' ),    array(), '3.3.5.1');
                   
        if ( $where_to_load == 'admin' ) {                                                                                                      // Admin CSS files            

            wp_enqueue_style('wpbc-chosen',                 wpbc_plugin_url( '/assets/libs/chosen/chosen.css' ),        array(), WP_BK_VERSION_NUM);
            wp_enqueue_style( 'wpbc-admin-support',         wpbc_plugin_url( '/core/any/css/admin-support.css' ),       array(), WP_BK_VERSION_NUM);            
            wp_enqueue_style( 'wpbc-admin-menu',            wpbc_plugin_url( '/core/any/css/admin-menu.css' ),          array(), WP_BK_VERSION_NUM);
            wp_enqueue_style( 'wpbc-admin-toolbar',         wpbc_plugin_url( '/core/any/css/admin-toolbar.css' ),       array(), WP_BK_VERSION_NUM);
            wp_enqueue_style( 'wpbc-settings-page',         wpbc_plugin_url( '/core/any/css/settings-page.css' ),       array(), WP_BK_VERSION_NUM);            
            wp_enqueue_style( 'wpbc-admin-listing-table',   wpbc_plugin_url( '/core/any/css/admin-listing-table.css' ), array(), WP_BK_VERSION_NUM);            
            wp_enqueue_style( 'wpbc-br-table',              wpbc_plugin_url( '/core/any/css/admin-br-table.css' ),      array(), WP_BK_VERSION_NUM);                        
            wp_enqueue_style( 'wpbc-admin-modal-popups',    wpbc_plugin_url( '/css/modal.css' ),                        array(), WP_BK_VERSION_NUM);            
            wp_enqueue_style( 'wpbc-admin-pages',           wpbc_plugin_url( '/css/admin.css' ),                        array(), WP_BK_VERSION_NUM);            
            wp_enqueue_style( 'wpbc-admin-skin',            wpbc_plugin_url( '/css/admin-skin.css' ),                   array( 'wpbc-admin-pages' ), WP_BK_VERSION_NUM);            //FixIn: 8.0.2.4
            wp_enqueue_style( 'wpbc-css-print',             wpbc_plugin_url( '/css/print.css' ),                        array(), WP_BK_VERSION_NUM);
        }         
        if (  ( $where_to_load != 'admin' ) || ( wpbc_is_new_booking_page() )  ){                                                               // Client or Add New Booking page
            wp_enqueue_style( 'wpbc-client-pages',          wpbc_plugin_url( '/css/client.css' ),                       array(), WP_BK_VERSION_NUM);            
        }        
        if (  ( $where_to_load != 'admin' ) || ( wpbc_is_bookings_page() )  ){                                                                  // Client or Booking Listing / Timeline pages
            wp_enqueue_style( 'wpbc-admin-timeline',        wpbc_plugin_url( '/css/timeline.css' ),                     array(), WP_BK_VERSION_NUM);                        
        }        
        wp_enqueue_style('wpbc-calendar',   wpbc_plugin_url( '/css/calendar.css' ),                                     array(), WP_BK_VERSION_NUM);        
                                                                                                                                                // Calendar Skins
        $calendar_skin_path = wpbc_get_calendar_skin_url();
        if ( ! empty( $calendar_skin_path ) )
            wp_enqueue_style('wpbc-calendar-skin', $calendar_skin_path ,                                                array(), WP_BK_VERSION_NUM);
    
        do_action( 'wpbc_enqueue_css_files', $where_to_load );        
    }


    public function remove_conflicts( $where_to_load ) {

    	//FixIn: 8.1.3.12
        if (
        	     wpbc_is_bookings_page()
        	  || wpbc_is_new_booking_page()
        	  || wpbc_is_resources_page()
        	  || wpbc_is_settings_page()
           ) {
            if (function_exists('wp_dequeue_style')) {
                /*
                wp_dequeue_style( 'cs-alert' );
                wp_dequeue_style( 'cs-framework' );
                wp_dequeue_style( 'cs-font-awesome' );
                wp_dequeue_style( 'icomoon' );           
                */            
                wp_dequeue_style( 'chosen'); 
                wp_dequeue_style( 'toolset-font-awesome-css' );                               // Remove this script sitepress-multilingual-cms/res/css/font-awesome.min.css?ver=3.1.6, which is load by the "sitepress-multilingual-cms"
                wp_dequeue_style( 'toolset-font-awesome' );                          //FixIn: 5.4.5.8
                wp_dequeue_style( 'the7-fontello-css' );
					wp_dequeue_style( 'dt-awsome-fonts-back-css' );                 //FixIn: 8.2.1.10           fix conflict  with https://the7.io/
	                wp_dequeue_style( 'dt-awsome-fonts-css' );
	                wp_dequeue_style( 'dt-fontello-css' );
                wp_dequeue_style( 'cs_icons_data_css_default');                         //FixIn: 8.1.3.12
	            wp_dequeue_style( 'icons-style' );                                      //FixIn: 8.2.1.22
	            wp_dequeue_style( 'fontawesome-style' );                                //FixIn: 8.2.1.22
	            wp_dequeue_style( 'bootstrap-style' );                                  //FixIn: 8.2.1.22
	            wp_dequeue_style( 'bootstrap-theme-style' );                            //FixIn: 8.2.1.22

            } 
        }
    }
}


/**
 * Get URL to  Calendar Skin ( CSS file )
 *
 * @return string - URL to  calendar skin
 */
function wpbc_get_calendar_skin_url() {
    
    // Calendar Skin ///////////////////////////////////////////////////////
    $calendar_skin_path = false;        
    // Check if this skin exist in the plugin  folder //////////////////////
    if ( file_exists( WPBC_PLUGIN_DIR . str_replace( WPBC_PLUGIN_URL, '', get_bk_option( 'booking_skin') ) ) ) {
        $calendar_skin_path = WPBC_PLUGIN_URL . str_replace( WPBC_PLUGIN_URL, '', get_bk_option( 'booking_skin') );
    }

    // Check  if this skin exist  int he Custom User folder at  the http://example.com/wp-content/uploads/wpbc_skins/
    $upload_dir = wp_upload_dir(); 
    $custom_user_skin_folder = $upload_dir['basedir'] ;
    $custom_user_skin_url    = $upload_dir['baseurl'] ;
    if ( file_exists( $custom_user_skin_folder . str_replace(  array( WPBC_PLUGIN_URL , $custom_user_skin_url ), '', get_bk_option( 'booking_skin') ) ) ) {
        $calendar_skin_path = $custom_user_skin_url . str_replace( array(WPBC_PLUGIN_URL, $custom_user_skin_url ), '', get_bk_option( 'booking_skin') );
    }

    return $calendar_skin_path;
}