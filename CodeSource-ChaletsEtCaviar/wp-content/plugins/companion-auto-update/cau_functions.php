<?php

// List of incompatible plugins
function cau_incompatiblePluginlist() {

	// Pluginlist, write as Plugin path => Issue
	$pluginList = array( 
		'better-wp-security/better-wp-security.php' => 'May block auto-updating for everything.', 
		'updraftplus/updraftplus.php' 				=> 'Does not update automatically, needs to be done manually. Causes no issues with other plugins.' 
	);

	return $pluginList;

}
function cau_incompatiblePlugins() {

	$return	= false;

	foreach ( cau_incompatiblePluginlist() as $key => $value ) {
		if( is_plugin_active( $key ) ) {
			$return = true;
		}
	}

	return $return;

}

// Check if has issues
function cau_pluginHasIssues() {

	$return = false;

	if( get_option( 'blog_public' ) == 0 ) {
		$return 	= true;
	}

	if( checkAutomaticUpdaterDisabled() ) {
		$return 	= true;
	}

	return $return;
}
function cau_pluginIssueLevels() {
	
	if( cau_incompatiblePlugins() OR get_option( 'blog_public' ) == 0 ) {
		$level 		= 'low';
	}

	if( checkAutomaticUpdaterDisabled() ) {
		$level 		= 'high';
	}

	return $level;
}
function cau_pluginIssueCount() {
	
	$count = 0;

	if( get_option( 'blog_public' ) == 0 ) {
		$count++;
	}
	if( checkAutomaticUpdaterDisabled() ) {
		$count++;
	}
	if( cau_incompatiblePlugins() ) {
		foreach ( cau_incompatiblePluginlist() as $key => $value ) {
			if( is_plugin_active( $key ) ) {
				$count++;
			}
		}
	}

	return $count;
}

// Run custom hooks on plugin update
function cau_run_custom_hooks_p() {

	// Create array
	$allDates 	= array();

	// Where to look for plugins
	$dirr    	= plugin_dir_path( __DIR__ );
	$listOfAll 	= get_plugins();

	// Loop trough all plugins
	foreach ( $listOfAll as $key => $value) {

		// Get data
		$fullPath 		= $dirr.'/'.$key;
		$fileDate 		= date ( 'YmdHi', filemtime( $fullPath ) );
		$updateSched 	= wp_get_schedule( 'wp_update_plugins' );

		// Check when the last update was
		if( $updateSched == 'hourly' ) {
			$lastday = date( 'YmdHi', strtotime( '-1 hour' ) );
		} elseif( $updateSched == 'twicedaily' ) {
			$lastday = date( 'YmdHi', strtotime( '-12 hours' ) );
		} elseif( $updateSched == 'daily' ) {
			$lastday = date( 'YmdHi', strtotime( '-1 day' ) );
		}

		// Push to array
		if( $fileDate >= $lastday ) {
			array_push( $allDates, $fileDate );
		}

	}

	$totalNum = 0;

	// Count number of updated plugins
	foreach ( $allDates as $key => $value ) $totalNum++;

	// If there have been plugin updates run hook
	if( $totalNum > 0 ) {
		do_action( 'cau_after_plugin_update' );
	}

}

// Run custom hooks on theme update
function cau_run_custom_hooks_t() {

	// Create array
	$allDates 	= array();

	// Where to look for plugins
	$dirr    	= get_theme_root();
	$listOfAll 	= wp_get_themes();

	// Loop trough all plugins
	foreach ( $listOfAll as $key => $value) {

		// Get data
		$fullPath 		= $dirr.'/'.$key;
		$fileDate 		= date ( 'YmdHi', filemtime( $fullPath ) );
		$updateSched 	= wp_get_schedule( 'wp_update_themes' );

		// Check when the last update was
		if( $updateSched == 'hourly' ) {
			$lastday = date( 'YmdHi', strtotime( '-1 hour' ) );
		} elseif( $updateSched == 'twicedaily' ) {
			$lastday = date( 'YmdHi', strtotime( '-12 hours' ) );
		} elseif( $updateSched == 'daily' ) {
			$lastday = date( 'YmdHi', strtotime( '-1 day' ) );
		}

		// Push to array
		if( $fileDate >= $lastday ) {
			array_push( $allDates, $fileDate );
		}

	}

	$totalNum = 0;

	// Count number of updated plugins
	foreach ( $allDates as $key => $value ) $totalNum++;

	// If there have been plugin updates run hook
	if( $totalNum > 0 ) {
		do_action( 'cau_after_theme_update' );
	}

}

// Check if automatic updating is disabled globally
function checkAutomaticUpdaterDisabled() {

	// I mean, I know this can be done waaaay better but I's quite late and I need to push a fix so take it or leave it untill I decide to fix this :)

	if ( defined( 'automatic_updater_disabled' ) ) {
		if( doing_filter( 'automatic_updater_disabled' ) ) {
			return true;
		} elseif( constant( 'automatic_updater_disabled' ) == 'true' ) {
			return true;
		} elseif( constant( 'automatic_updater_disabled' ) == 'minor' ) {
			return true;
		} else {
			return false;
		}

	} else if ( defined( 'AUTOMATIC_UPDATER_DISABLED' ) ) {
		if( doing_filter( 'AUTOMATIC_UPDATER_DISABLED' ) ) {
			return true;
		} elseif( constant( 'AUTOMATIC_UPDATER_DISABLED' ) == 'true' ) {
			return true;
		} elseif( constant( 'AUTOMATIC_UPDATER_DISABLED' ) == 'minor' ) {
			return true;
		} else {
			return false;
		}

	} else {
		return false;
	}

}

// Menu location
function cau_menloc() {
	return 'tools.php';
}

// Get the active tab
function active_tab( $page, $identifier = 'tab' ) {

	if( !isset( $_GET[ $identifier ] ) ) {
		$cur_page = '';
	} else {
		$cur_page = $_GET[ $identifier ];
	}

	if( $page == $cur_page ) {
		echo 'nav-tab-active';
	}

}

// Get the active subtab
function active_subtab( $page, $identifier = 'tab' ) {

	if( !isset( $_GET[ $identifier ] ) ) {
		$cur_page = '';
	} else {
		$cur_page = $_GET[ $identifier ];
	}

	if( $page == $cur_page ) {
		echo 'current';
	}

}

// List of plugins that should not be updated
function donotupdatelist() {

	global $wpdb;
	$table_name 	= $wpdb->prefix . "auto_updates"; 
	$config 		= $wpdb->get_results( "SELECT * FROM $table_name WHERE name = 'notUpdateList'");

	$list 			= $config[0]->onoroff;
	$list 			= explode( ", ", $list );
	$returnList 	= array();

	foreach ( $list as $key ) array_push( $returnList, $key );
	
	return $returnList;

}

// Show the update log
function cau_fetch_log( $limit, $format = 'simple' ) {

	// Create arrays
	$pluginNames 	= array();
	$pluginVersion 	= array();
	$pluginDates 	= array();
	$pluginDatesF 	= array();
	$type 			= array();

	// Where to look for plugins
	$plugdir    = plugin_dir_path( __DIR__ );
	$allPlugins = get_plugins();

	// Date format
	$dateFormat = get_option( 'date_format' );

	// Loop trough all plugins
	foreach ( $allPlugins as $key => $value) {

		// Get plugin data
		$fullPath 	= $plugdir.'/'.$key;
		$getFile 	= $path_parts = pathinfo( $fullPath );
		$pluginData = get_plugin_data( $fullPath );

		// Get plugin name
		foreach ( $pluginData as $dataKey => $dataValue ) {
			if( $dataKey == 'Name') {
				array_push( $pluginNames , $dataValue );
			}
			if( $dataKey == 'Version') {
				array_push( $pluginVersion , $dataValue );
			}
		}

		// Get last update date
		$fileDate 	= date ( 'YmdHi', filemtime( $fullPath ) );
		if( $format == 'table' ) {
			$fileDateF 	= date_i18n ( $dateFormat, filemtime( $fullPath ) );
			$fileDateF .= ' &dash; '.date ( 'H:i', filemtime( $fullPath ) );
		} else {
			$fileDateF 	= date_i18n ( $dateFormat, filemtime( $fullPath ) );
		}
		array_push( $pluginDates, $fileDate );
		array_push( $pluginDatesF, $fileDateF );
		array_push( $type, 'Plugin' );

	}

	// Where to look for themes
	$themedir   = get_theme_root();
	$allThemes 	= wp_get_themes();

	// Loop trough all themes
	foreach ( $allThemes as $key => $value) {

		// Get theme data
		$fullPath 	= $themedir.'/'.$key;
		$getFile 	= $path_parts = pathinfo( $fullPath );

		// Get theme name
		$theme_data 	= wp_get_theme( $path_parts['filename'] );
		$themeName 		= $theme_data->get( 'Name' );
		$themeVersion 	= $theme_data->get( 'Version' ); 
		array_push( $pluginNames , $themeName ); 
		array_push( $pluginVersion , $themeVersion );


		// Get last update date
		$fileDate 	= date ( 'YmdHi', filemtime( $fullPath ) );

		if( $format == 'table' ) {
			$fileDateF 	= date_i18n ( $dateFormat, filemtime( $fullPath ) );
			$fileDateF .= ' &dash; '.date ( 'H:i', filemtime( $fullPath ) );
		} else {
			$fileDateF 	= date_i18n ( $dateFormat, filemtime( $fullPath ) );
		}

		array_push( $pluginDates, $fileDate );
		array_push( $pluginDatesF, $fileDateF );
		array_push( $type, 'Theme' );

	}

	// Core
	// There is no way (at this time) to check if someone changed this link, so therefore it won't work when it's changed, sorry
	$coreFile = get_home_path().'wp-admin/about.php';
	if( file_exists( $coreFile ) ) {
		$coreDate 	= date ( 'YmdHi', filemtime( $coreFile ) );
		$coreDateF 	= date_i18n ( $dateFormat, filemtime( $coreFile ) );
		$coreDateF .= ' &dash; '.date ( 'H:i', filemtime( $coreFile ) );
	} else {
		$coreDate 	= date('YmdHi');
		$coreDateF 	= 'Could not read core date.';
	}

	array_push( $pluginNames, 'WordPress' ); 
	array_push( $type, 'WordPress' ); 
	array_push( $pluginVersion, get_bloginfo( 'version' ) );
	array_push( $pluginDates, $coreDate );
	array_push( $pluginDatesF, $coreDateF );

	// Sort array by date
	arsort( $pluginDates );

	if( $limit == 'all' ) {
		$limit = 999;
	}

	$listClasses = 'wp-list-table widefat autoupdate autoupdatelog';

	if( $format == 'table' ) {
		$listClasses .= ' autoupdatelog striped';
	} else {
		$listClasses .= ' autoupdatewidget';
	}

	echo '<table class="'.$listClasses.'">';

	// Show the last updated plugins
	if( $format == 'table' ) {

		echo '<thead>
			<tr>
				<th><strong>'.__( 'Name', 'companion-auto-update' ).'</strong></th>
				<th><strong>'.__( 'Type', 'companion-auto-update' ).'</strong></th>
				<th><strong>'.__( 'To', 'companion-auto-update' ).'</strong></th>
				<th><strong>'.__( 'Last updated on', 'companion-auto-update' ).'</strong></th>
			</tr>
		</thead>';

	}

	echo '<tbody id="the-list">';

	$loopings = 0;

	foreach ( $pluginDates as $key => $value ) {

		if( $loopings < $limit ) {

			echo '<tr>';

				if( $format == 'table' ) {
					$pluginName = $pluginNames[$key];
				} else {
					$pluginName = substr( $pluginNames[$key], 0, 25);
					if( strlen( $pluginNames[$key] ) > 25 ) {
						$pluginName .= '...';
					}
				}

				echo '<td class="column-updatetitle"><p><strong title="'. $pluginNames[$key] .'">'. $pluginName .'</strong></p></td>';

				if( $format == 'table' ) {

					if( $type[$key] == 'Plugin' ) {
						$thisType = __( 'Plugin', 'companion-auto-update' );
					} else if( $type[$key] == 'Theme' ) {
						$thisType = __( 'Theme', 'companion-auto-update' );
					} else {
						$thisType = $type[$key];
					}

					echo '<td class="cau_hide_on_mobile column-description"><p>'. $thisType .'</p></td>';
					echo '<td class="cau_hide_on_mobile column-version" style="min-width: 100px;"><p>'. $pluginVersion[$key] .'</p></td>';

				}

				echo '<td class="column-date" style="min-width: 100px;"><p>'. $pluginDatesF[$key] .'</p></td>';

			echo '</tr>';

			$loopings++;

		}

	}

	echo "</tbody></table>";

}

// Only update plugins which are enabled
function cau_dont_update( $update, $item ) {

	$plugins = donotupdatelist();

    if ( in_array( $item->slug, $plugins ) ) {
		// Use the normal API response to decide whether to update or not
    	return $update; 
    } else {
    	// Always update plugins
    	return true; 
    } 

}

// Get plugin information of repository
function cau_plugin_info( $slug, $what ) {

	$slug 				= sanitize_title( $slug );
    $cau_transient_name = 'cau' . $slug;
    $cau_info 			= get_transient( $cau_transient_name );

	require_once( plugin_dir_path( __FILE__ ) . 'wp-admin/includes/plugin-install.php' );
	$cau_info = plugins_api( 'plugin_information', array( 'slug' => $slug ) );

	if ( ! $cau_info or is_wp_error( $cau_info ) ) {
        return false;
    }

    set_transient( $cau_transient_name, $cau_info, 3600 );

    if( $what == 'versions' ) return $cau_info->versions;
    if( $what == 'version' ) return $cau_info->version;
    if( $what == 'name' ) return $cau_info->name;

}

// Get plugin information of currently installed plugins
function cau_active_plugin_info( $slug, $what ) {

	$allPlugins = get_plugins();

	foreach ($allPlugins as $key => $value) {
		$thisSlug 	= explode('/', $key);
		$thisSlugE 	= $thisSlug[0];
		if( $thisSlug == $slug ) {

			if( $what == 'version' ) return $value['Version'];

		}
	}

}

// Remove update nag when major updates are disabled
function cau_hideUpdateNag() {

	global $wpdb;
	$table_name = $wpdb->prefix . "auto_updates"; 
	$configs 	= $wpdb->get_results( "SELECT * FROM $table_name WHERE name = 'major'");
	foreach ( $configs as $config ) {
		if( $config->onoroff != 'on' ) {
			remove_action( 'admin_notices', 'update_nag', 3 );
			remove_action( 'network_admin_notices', 'maintenance_nag', 10 );
		}
	}
}
 
add_action( 'admin_head', 'cau_hideUpdateNag', 100 );

?>