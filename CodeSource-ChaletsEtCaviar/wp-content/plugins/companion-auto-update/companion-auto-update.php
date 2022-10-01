<?php
/*
 * Plugin Name: Companion Auto Update
 * Plugin URI: http://codeermeneer.nl/portfolio/companion-auto-update/
 * Description: This plugin auto updates all plugins, all themes and the wordpress core.
 * Version: 3.3.6
 * Author: Papin Schipper
 * Author URI: http://codeermeneer.nl/
 * Contributors: papin
 * License: GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: companion-auto-update
 * Domain Path: /languages/
*/

// Disable direct access
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

// Load translations
function cau_load_translations() {
	load_plugin_textdomain( 'companion-auto-update', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' ); 
}
add_action( 'init', 'cau_load_translations' );

// Set up the database and required schedules
function cau_install() {
	cau_database_creation(); // Db handle
	if (! wp_next_scheduled ( 'cau_set_schedule_mail' )) wp_schedule_event( time(), 'daily', 'cau_set_schedule_mail'); // Set schedule for mail etc.
	if (! wp_next_scheduled ( 'cau_custom_hooks_plugins' )) wp_schedule_event( time(), 'daily', 'cau_custom_hooks_plugins'); // Run custom hooks on plugin updates
	if (! wp_next_scheduled ( 'cau_custom_hooks_themes' )) wp_schedule_event( time(), 'daily', 'cau_custom_hooks_themes'); // Run custom hooks on theme updates
}
add_action('cau_set_schedule_mail', 'cau_check_updates_mail');
add_action('cau_custom_hooks_plugins', 'cau_run_custom_hooks_p');
add_action('cau_custom_hooks_themes', 'cau_run_custom_hooks_t');

// Redirect to welcome screen on activation of plugin
function cau_pluginActivateWelcome() {
    add_option( 'cau_redirectToWelcomeScreen', true );
}
register_activation_hook(__FILE__, 'cau_pluginActivateWelcome');

function cau_pluginRedirectWelcomeScreen() {
    if ( get_option( 'cau_redirectToWelcomeScreen', false ) ) {
        delete_option( 'cau_redirectToWelcomeScreen' );
        if( !isset( $_GET['activate-multi'] ) ) {
            wp_redirect( admin_url( cau_menloc().'?page=cau-settings&welcome=1' ) );
        }
    }
}
add_action('admin_init', 'cau_pluginRedirectWelcomeScreen');

// Donate url
function cau_donateUrl() {
	return 'https://www.paypal.me/dakel/5/';
}

// Create database
function cau_database_creation() {

	global $wpdb;
	global $cau_db_version;

	$cau_db_version = '1.4.4';

	// Create db table
	$table_name = $wpdb->prefix . "auto_updates"; 

	$sql = "CREATE TABLE $table_name (
		id INT(9) NOT NULL AUTO_INCREMENT,
		name VARCHAR(255) NOT NULL,
		onoroff VARCHAR(99999) NOT NULL,
		UNIQUE KEY id (id)
	)";

	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta( $sql );

	// Database version
	add_option( "cau_db_version", "$cau_db_version" );

	// Insert data
	cau_install_data();

	// Updating..
	$installed_ver = get_option( "cau_db_version" );
	if ( $installed_ver != $cau_db_version ) update_option( "cau_db_version", $cau_db_version );

}

// Check if database table exists before creating
function cau_check_if_exists( $whattocheck ) {

	global $wpdb;
	$table_name = $wpdb->prefix . "auto_updates"; 

	$rows 	= $wpdb->get_col( "SELECT COUNT(*) as num_rows FROM $table_name WHERE name = '$whattocheck'" );
	$check 	= $rows[0];

	if( $check > 0) {
		return true;
	} else {
		return false;
	}

}

// Insert date into database
function cau_install_data() {

	global $wpdb;
	$table_name = $wpdb->prefix . "auto_updates"; 
	$toemail 	= get_option('admin_email');

	// Update configs
	if( !cau_check_if_exists( 'plugins' ) ) $wpdb->insert( $table_name, array( 'name' => 'plugins', 'onoroff' => 'on' ) );
	if( !cau_check_if_exists( 'themes' ) ) $wpdb->insert( $table_name, array( 'name' => 'themes', 'onoroff' => 'on' ) );
	if( !cau_check_if_exists( 'minor' ) ) $wpdb->insert( $table_name, array( 'name' => 'minor', 'onoroff' => 'on' ) );
	if( !cau_check_if_exists( 'major' ) ) $wpdb->insert( $table_name, array( 'name' => 'major', 'onoroff' => '' ) );

	// Email configs
	if( !cau_check_if_exists( 'email' ) ) $wpdb->insert( $table_name, array( 'name' => 'email', 'onoroff' => '' ) );
	if( !cau_check_if_exists( 'send' ) ) $wpdb->insert( $table_name, array( 'name' => 'send', 'onoroff' => '' ) );
	if( !cau_check_if_exists( 'sendupdate' ) ) $wpdb->insert( $table_name, array( 'name' => 'sendupdate', 'onoroff' => '' ) );

	// Advanced
	if( !cau_check_if_exists( 'notUpdateList' ) ) $wpdb->insert( $table_name, array( 'name' => 'notUpdateList', 'onoroff' => '' ) );
	if( !cau_check_if_exists( 'translations' ) ) $wpdb->insert( $table_name, array( 'name' => 'translations', 'onoroff' => 'on' ) );
	if( !cau_check_if_exists( 'wpemails' ) ) $wpdb->insert( $table_name, array( 'name' => 'wpemails', 'onoroff' => 'on' ) );

}
register_activation_hook( __FILE__, 'cau_install' );

// Clear everything on deactivation
function cau_remove() {
	global $wpdb;
	$table_name = $wpdb->prefix . "auto_updates"; 
	$wpdb->query( "DROP TABLE IF EXISTS $table_name" );
	wp_clear_scheduled_hook('cau_set_schedule_mail');
}
register_deactivation_hook(  __FILE__, 'cau_remove' );

// Update
function cau_update_db_check() {
    global $cau_db_version;
    if ( get_site_option( 'cau_db_version' ) != $cau_db_version ) {
        cau_database_creation();
    }
}
add_action( 'plugins_loaded', 'cau_update_db_check' );

// Load custom functions
require_once( plugin_dir_path( __FILE__ ) . 'cau_functions.php' );

// Add plugin to menu
function register_cau_menu_page() {
	add_submenu_page( cau_menloc() , __('Auto Updater', 'companion-auto-update'), __('Auto Updater', 'companion-auto-update'), 'manage_options', 'cau-settings', 'cau_frontend' );
}
add_action( 'admin_menu', 'register_cau_menu_page' );

// Settings page
function cau_frontend() { ?>
	
	<div class='wrap cau_content_wrap'>

		<h1 class="wp-heading-inline"><?php _e('Companion Auto Update', 'companion-auto-update'); ?></h1>

		<div class='cau_support_buttons'>
	 		<a href="<?php echo cau_donateUrl(); ?>" target="_blank" class="donate-button page-title-action cau_hide_on_mobile"><?php _e('Donate to help development', 'companion-auto-update'); ?></a>
	 	</div>

		<hr class="wp-header-end">

		<h2 class="nav-tab-wrapper wp-clearfix">
			<a href="<?php echo cau_menloc(); ?>?page=cau-settings" class="nav-tab <?php active_tab(''); ?>"><?php _e('Dashboard' ); ?></a>
			<a href="<?php echo cau_menloc(); ?>?page=cau-settings&amp;tab=schedule&cau_page=advanced" class="nav-tab <?php active_tab('schedule', 'tab'); ?>"><?php _e('Advanced settings', 'companion-auto-update'); ?></a>
			<a href="<?php echo cau_menloc(); ?>?page=cau-settings&amp;tab=pluginlist&cau_page=advanced" class="nav-tab <?php active_tab('pluginlist', 'tab'); ?>"><?php _e('Select plugins', 'companion-auto-update'); ?></a>
			<a href="<?php echo cau_menloc(); ?>?page=cau-settings&amp;tab=log&amp;cau_page=log" class="systeminfoTab nav-tab <?php active_tab('log', 'cau_page'); ?>"><?php _e('Update log', 'companion-auto-update'); ?></a>
			<a href="<?php echo cau_menloc(); ?>?page=cau-settings&amp;tab=status&amp;cau_page=status" class="systeminfoTab nav-tab <?php active_tab('status', 'cau_page'); ?>"><?php _e('Status', 'companion-auto-update'); ?></a>
			<a href="<?php echo cau_menloc(); ?>?page=cau-settings&amp;tab=support" class="nav-tab <?php active_tab('support'); ?>"><?php _e('Support', 'companion-auto-update'); ?></a>
		</h2>

		<?php

		if( !isset( $_GET['tab'] ) ) {

			require_once( plugin_dir_path( __FILE__ ) . 'admin/dashboard.php' );

		} else {

			$requestedPage 	= $_GET['tab'];
			$allowedPages 	= array( 'dashboard', 'log', 'pluginlist', 'rollback', 'schedule', 'status', 'support' );

			if( in_array( $requestedPage, $allowedPages) ) {
				require_once( plugin_dir_path( __FILE__ ) . 'admin/'.$requestedPage.'.php' );
			} else {
				wp_die( 'You\'re not allowed to view <strong>'.$requestedPage.'</strong>.' );				
			}

		} ?>

	</div>

<?php }

// Add a widget to the dashboard.
function cau_add_widget() {
	if ( current_user_can( 'manage_options' ) ) wp_add_dashboard_widget( 'cau-update-log', __('Update log', 'companion-auto-update'), 'cau_widget' );	
}
add_action( 'wp_dashboard_setup', 'cau_add_widget' );

function cau_widget() {

	echo '<style>table.autoupdatewidget { border: 0px solid transparent; border-bottom: 1px solid #EEEEEE; margin: 0 -12px; width: calc(100% + 24px); } table.autoupdatewidget tr td { border-top: 1px solid #EEEEEE; padding: 9px 12px 5px 12px; background: #FAFAFA; } .cau_divide { display: inline-block; color: #E7E0DF; padding: 0 2px; } </style>';
	echo '<p>'.__('Below are the last 7 updates ran on this site. Includes plugins and themes, both automatically updated and manually updated.', 'companion-auto-update').'</p>';
	cau_fetch_log( '7' );
	echo '<p>
		<a href="'.admin_url( cau_menloc().'?page=cau-settings&tab=log&cau_page=log').'">'.__('View full changelog', 'companion-auto-update').'</a> 
		<span class="cau_divide">|</span> 
		<a href="'.admin_url( cau_menloc().'?page=cau-settings').'">'.__( 'Settings' ).'</a>
	</p>';
	
}

// Load admin styles
function load_cau_sytyles( $hook ) {

    if( $hook != 'tools_page_cau-settings' && $hook != 'index_page_cau-settings' ) return;
    wp_enqueue_style( 'cau_admin_styles', plugins_url( 'backend/style.css' , __FILE__ ) );

}
add_action( 'admin_enqueue_scripts', 'load_cau_sytyles', 100 );

// Send e-mails
require_once( plugin_dir_path( __FILE__ ) . 'cau_emails.php' );

// Add settings link on plugin page
function cau_settings_link( $links ) { 

	$settings_link 	= '<a href="'.get_admin_url().''.cau_menloc().'?page=cau-settings">'.__( 'Settings' ).'</a>'; 
	$settings_link2 = '<a href="https://translate.wordpress.org/projects/wp-plugins/companion-auto-update">'.__( 'Help us translate', 'companion-auto-update' ).'</a>'; 
	$settings_link3 = '<a href="'.cau_donateUrl().'">'.__( 'Donate to help development', 'companion-auto-update' ).'</a>'; 
	
	array_unshift( $links, $settings_link2 ); 
	array_unshift( $links, $settings_link3 ); 
	array_unshift( $links, $settings_link ); 

	return $links; 

}
$plugin = plugin_basename(__FILE__); 
add_filter( "plugin_action_links_$plugin", "cau_settings_link" );

// Auto Update Class
class CAU_auto_update {

	public function __construct() {
	
        // Enable Update filters
        add_action( 'plugins_loaded', array( &$this, 'CAU_auto_update_filters' ), 1 );

    }

    public function CAU_auto_update_filters() {

		global $wpdb;
		$table_name = $wpdb->prefix . "auto_updates"; 

		// Enable for major updates
		$configs = $wpdb->get_results( "SELECT * FROM $table_name WHERE name = 'major'");
		foreach ( $configs as $config ) {

			if( $config->onoroff == 'on' ) add_filter( 'allow_major_auto_core_updates', '__return_true', 1 ); // Turn on
			if( $config->onoroff != 'on' ) add_filter( 'allow_major_auto_core_updates', '__return_false', 1 ); // Turn off

		}

		// Enable for minor updates
		$configs = $wpdb->get_results( "SELECT * FROM $table_name WHERE name = 'minor'");
		foreach ( $configs as $config ) {

			if( $config->onoroff == 'on' ) add_filter( 'allow_minor_auto_core_updates', '__return_true', 1 ); // Turn on
			if( $config->onoroff != 'on' ) add_filter( 'allow_minor_auto_core_updates', '__return_false', 1 ); // Turn off

		}

		// Enable for plugins
		$configs = $wpdb->get_results( "SELECT * FROM $table_name WHERE name = 'plugins'");
		foreach ( $configs as $config ) {

			if( $config->onoroff == 'on' ) add_filter( 'auto_update_plugin', 'cau_dont_update', 10, 2 ); // Turn on
			if( $config->onoroff != 'on' ) add_filter( 'auto_update_plugin', '__return_false', 1 ); // Turn off

		}

		// Enable for themes
		$configs = $wpdb->get_results( "SELECT * FROM $table_name WHERE name = 'themes'");
		foreach ( $configs as $config ) {
			if( $config->onoroff == 'on' ) add_filter( 'auto_update_theme', '__return_true', 1 ); // Turn on
			if( $config->onoroff != 'on' ) add_filter( 'auto_update_theme', '__return_false', 1 ); // Turn off
		}

		// Enable for translation files
		$configs = $wpdb->get_results( "SELECT * FROM $table_name WHERE name = 'translations'");
		foreach ( $configs as $config ) {
			if( $config->onoroff == 'on' ) add_filter( 'auto_update_translation', '__return_true', 1 ); // Turn on
			if( $config->onoroff != 'on' ) add_filter( 'auto_update_translation', '__return_false', 1 ); // Turn off
		}

		// WP Email Config
		$configs = $wpdb->get_results( "SELECT * FROM $table_name WHERE name = 'wpemails'");
		foreach ( $configs as $config ) {
			if( $config->onoroff == 'on' ) add_filter( 'auto_core_update_send_email', '__return_true' ); // Turn on
			if( $config->onoroff != 'on' ) add_filter( 'auto_core_update_send_email', '__return_false' ); // Turn off
		}
		

	}

}
new CAU_auto_update();

// Check for issues
function cau_checkForIssues( $admin_bar ) {

	if( cau_pluginHasIssues() && is_admin() ) {

		if( cau_pluginIssueLevels() == 'high' ) {
			$cauWaningBarTitle = __( 'Companion Auto Update ran into a critical error. View the status log for more info.', 'companion-auto-update' );
		} else {
			$cauWaningBarTitle = '';
		}

		$admin_bar->add_menu( array(
	        'id'    => 'cau-has-issues',
	        'title' => '<span class="ab-icon"></span><span class="cau-level-'.cau_pluginIssueLevels().'">'.cau_pluginIssueCount().'</span>',
	        'href'  => admin_url( cau_menloc().'?page=cau-settings&tab=status&cau_page=status' ),       
	        'meta'   => array(
	            'target'   => '_self',
	            'title'    => $cauWaningBarTitle,
	        ),
	    ));

	}

}
add_action( 'admin_bar_menu', 'cau_checkForIssues', 150 );

function cau_checkForIssuesStyle() {

    wp_enqueue_style( 'cau_warning_styles', plugins_url( 'backend/warningbar.css' , __FILE__ ) );

}
add_action( 'admin_enqueue_scripts', 'cau_checkForIssuesStyle', 100 );

?>