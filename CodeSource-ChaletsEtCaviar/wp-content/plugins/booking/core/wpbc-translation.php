<?php 
/**
 * @version 1.0
 * @package Booking Calendar 
 * @subpackage Translations Functions
 * @category Functions
 * 
 * @author wpdevelop
 * @link https://wpbookingcalendar.com/
 * @email info@wpbookingcalendar.com
 *
 * @modified 29.09.2015
 */

if ( ! defined( 'ABSPATH' ) ) exit;                                             // Exit if accessed directly

////////////////////////////////////////////////////////////////////////////////
//   Transaltions   ////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////

// New in WP 6.2 - firstly loaded transaltion  from  wp-content/languges and then chek  only 
// use the override_load_textdomain filter to load your text domains manually.
// apply_filters( 'override_load_textdomain', bool $override, string $domain, string $mofile )

/**
	 * Check text  for active language section
 * 
 * @param string $content_orig
 * @return string
 * Usage:
 * $text = apply_bk_filter('wpdev_check_for_active_language',  $text );
 */    
function wpdev_check_for_active_language($content_orig){

    $content = $content_orig;

    $languages = array();
    $content_ex = explode('[lang',$content);

    foreach ($content_ex as $value) {

        if (substr($value,0,1) == '=') {

            $pos_s = strpos($value,'=');
            $pos_f = strpos($value,']');
            $key = trim( substr($value, ($pos_s+1), ($pos_f-$pos_s-1) ) );
            $value_l = trim( substr($value,  $pos_f+1  ) );
            $languages[$key] = $value_l;

        } else  
            $languages['default'] = $value;
    }

    $locale = wpbc_get_booking_locale();
    // $locale = 'fr_FR';

    if ( isset( $languages[$locale] ) ) $return_text = $languages[ $locale ];
    else                                $return_text = $languages[ 'default' ];

    $return_text = wpdev_bk_check_qtranslate( $return_text, $locale );
    
    $return_text = wpbc_check_wpml_tags( $return_text, $locale );               //FixIn: 5.4.5.8
    
    return $return_text;
}


/**
	 * Register and Translate everything in [wpml]Some Text to translate[/wpml] tags.
 * 
 * @param string $text
 * @param string $locale
 * @return string
 */
function wpbc_check_wpml_tags( $text, $locale='' ) {                            //FixIn: 5.4.5.8

    if ( $locale == '' ) {
        $locale = wpbc_get_booking_locale();
    }
    if ( strlen( $locale ) > 2 ) {
        $locale = substr($locale, 0, 2 );
    }

    $is_tranlsation_exist_s = strpos( $text, '[wpml]' );
    $is_tranlsation_exist_f = strpos( $text, '[/wpml]' );

    if ( ( $is_tranlsation_exist_s !== false )  &&  ( $is_tranlsation_exist_f !== false ) )  {

        $shortcode = 'wpml';

        // Find anything between [wpml] and [/wpml] shortcodes. Magic here: [\s\S]*? - fit to any text
        preg_match_all( '/\[' . $shortcode . '\]([\s\S]*?)\[\/' . $shortcode . '\]/i', $text, $wpml_translations, PREG_SET_ORDER );               
//debuge( $wpml_translations );

        foreach ( $wpml_translations as $translation ) {                
            $text_to_replace      = $translation[0];
            $translation_to_check = $translation[1];

            if ( function_exists ( 'icl_register_string' ) ){

                if ( false ) {   // Depricated functions

                    // Help: https://wpml.org/documentation/support/translation-for-texts-by-other-plugins-and-themes/  
                    icl_register_string('Booking Calendar', 'wpbc-' . tag_escape( $translation_to_check ) , $translation_to_check );

                    //TODO: Need to  execurte this after deactivation  of plugin  or after updating of some option...
                    //icl_unregister_string ( 'Booking Calendar', 'wpbc-' . tag_escape( $translation_to_check ) );                      

                    if ( function_exists ( 'icl_translate' ) ){
                        $translation_to_check = icl_translate ( 'Booking Calendar', 'wpbc-' . tag_escape( $translation_to_check ) , $translation_to_check  );
                    }

                } else { // WPML Version: 3.2

                    // Help info:  do_action( 'wpml_register_single_string', string $context, string $name, string $value )
                    // https://wpml.org/wpml-hook/wpml_register_string_for_translation/
                    do_action( 'wpml_register_single_string', 'Booking Calendar', 'wpbc-' . tag_escape( $translation_to_check ) , $translation_to_check );


                    // Help info:  apply_filters( 'wpml_translate_single_string', string $original_value, string $context, string $name, string $$language_code )
                    // https://wpml.org/wpml-hook/wpml_translate_single_string/
                    //$translation_to_check = apply_filters( 'wpml_translate_single_string', $translation_to_check, 'Booking Calendar',  'wpbc-' . tag_escape( $translation_to_check ) );
                    $language_code = $locale;
                    $translation_to_check = apply_filters( 'wpml_translate_single_string', $translation_to_check, 'Booking Calendar',  'wpbc-' . tag_escape( $translation_to_check ), $language_code );

                }
            }                
            $text = str_replace( $text_to_replace, $translation_to_check, $text );
        }            
    }  

    return $text;
}

    
function wpdev_bk_check_qtranslate( $text, $locale='' ){
    
    if ($locale == '') {
        $locale = wpbc_get_booking_locale();
    }
    if (strlen($locale)>2) {
        $locale = substr($locale, 0 ,2);
    }

    $is_tranlsation_exist = strpos($text, '<!--:'.$locale.'-->');

    if ($is_tranlsation_exist !== false) {
        $tranlsation_end = strpos($text, '<!--:-->', $is_tranlsation_exist);

        $text = substr($text, $is_tranlsation_exist , ($tranlsation_end - $is_tranlsation_exist ) );
    }

    return $text;
}


function wpbc_load_translation(){
    
    //$locale = 'fr_FR'; wpbc_load_locale( $locale ); 
    
    if ( ! wpbc_load_locale() ) { 
        wpbc_load_locale('en_US');         
    }
    
    $locale = wpbc_get_booking_locale();
}


/**
	 * Overload loading of plugin transaltion  files from    "wp-content/plugins/languages" -> "wp-content/plugins/plugin_name/languages"
 * 
 * W:\home\beta\www/wp-content/languages/plugins/booking-it_IT.mo   ->   W:\home\beta\www\wp-content\plugins\booking/languages/booking-it_IT.mo
 * 
 * @param string $mofile
 * @param type $domain
 * @return string
 */
function wpbc_load_custom_plugin_translation_file( $mofile, $domain ) {

    if ( $domain == 'booking' ) {
        
// debuge( $mofile, basename( $mofile ) , WPBC_PLUGIN_DIR );        
        
        $mofile =  WPBC_PLUGIN_DIR . '/languages/' . basename( $mofile );         
    }

    return $mofile;
}
add_filter( 'load_textdomain_mofile', 'wpbc_load_custom_plugin_translation_file' , 10, 2 );


function wpbc_load_locale( $locale = '' ) { 
//debuge($locale);     
//if ( $locale == 'en_US')
//debuge($dfgdfg);

    if ( empty( $locale ) ) 
        $locale = wpbc_get_booking_locale();
//debuge($locale);    
    if ( ! empty( $locale ) ) {

        $domain = 'booking'; 
        $mofile = WPBC_PLUGIN_DIR  . '/languages/' . $domain . '-' . $locale . '.mo';
        
		if ( strpos( $locale, '_') !== false ) {		//FixIn: 7.1.2.11
			// we have long locale like en_US,  get  only 2 firstletters,  for general  locale,  like 'en'
			$mofile_local_short = WPBC_PLUGIN_DIR  . '/languages/' . $domain . '-' . substr( $locale, 0 , 2 ) . '.mo';
		}
		
//debuge( $mofile );		
        if ( file_exists( $mofile ) ) {
                                                                            
            $plugin_rel_path = WPBC_PLUGIN_DIRNAME . '/languages'  ;
//debuge(1,$domain, false, $plugin_rel_path )	;		
            return load_plugin_textdomain( $domain, false, $plugin_rel_path ) ;
			
        } elseif ( ( ! empty( $mofile_local_short ) ) && ( file_exists( $mofile_local_short ) ) ) {                     //FixIn: 8.1.3.13
//debuge(2,$domain, $mofile_local_short)       ;     
			// Direct  load of this short MO file		booking-en.mo
            return load_textdomain( $domain, $mofile_local_short ); 
		} else {														//FixIn: 7.2.1.21
			unload_textdomain( $domain );
		}
    }
//debuge( 3 ) ;   
    return false;
}


function wpbc_get_booking_locale() {

	// Exception for Polylang plugin. Its will force to load locale of Polylang plugin.
	if( function_exists( 'pll_current_language' ) ) {                                                                   //FixIn: 8.1.2.5
		$locale = pll_current_language('locale');
		return $locale;
	}

//debuge( get_locale(), get_user_locale() , WPBC_LOCALE_RELOAD );    //FixIn: 7.2.1.21
    if ( defined( 'WPBC_LOCALE_RELOAD' ) ) 
        return WPBC_LOCALE_RELOAD;
    
	if( function_exists( 'get_user_locale' ) )
		$locale = is_admin() ? get_user_locale() : get_locale();
	else
		$locale = get_locale();

	define( 'WPBC_LOCALE_RELOAD', $locale );
    
    return $locale;
}


function wpbc_recheck_plugin_locale( $locale, $plugin_domain ) {

    if ( $plugin_domain == 'booking' ) 
        if ( defined('WPBC_LOCALE_RELOAD') )
            return WPBC_LOCALE_RELOAD;

    return $locale;
}
add_filter( 'plugin_locale', 'wpbc_recheck_plugin_locale', 100, 2 );            // When load_plugin_text_domain is work, its get def locale and not that, we send to it so need to reupdate it

/**
	 * Get help rows about configuration in_several languges
 * 
 * @return array - each  item of array  is text row for showing.
 */
function wpbc_get_help_rows_about_config_in_several_languges() {
    
    $field_options = array();
    $field_options[] = '<strong>' . __('Configuration in several languages' ,'booking') . '</strong>';
    $field_options[] = sprintf(__('%s - start new translation section, where %s - locale of translation' ,'booking'),'<code>[lang=LOCALE]</code>','<code>LOCALE</code>');
    $field_options[] = sprintf(__('Example #1: %s - start French translation section' ,'booking'),'<code>[lang=fr_FR]</code>');
    $field_options[] = sprintf(__('Example #2: "%s" - English and French translation of some message' ,'booking'),'<code>Thank you for your booking.[lang=fr_FR]Je vous remercie de votre reservation.</code>');
    
    return $field_options;
}


